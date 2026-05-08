<?php

namespace App\Http\Controllers;

use App\Models\ProviderProfile;
use App\Models\SuperAdmin;
use App\Models\User;
use App\Support\ProviderProfileShowState;
use App\Support\ProviderStaffScope;
use App\Support\WorkshopCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class SuperAdminProviderController extends Controller
{
    public function index(Request $request)
    {
        $admin = SuperAdmin::query()->find(Session::get('super_admin_id'));
        $scope = ProviderStaffScope::allowedTypesFor($admin);
        if (is_array($scope) && $scope === []) {
            return redirect()->route('super-admin.dashboard')->with('toast', [
                'type' => 'warning',
                'message' => 'لا يوجد قطاع مزوّدين مفعّل لحسابك. راجع الإدارة.',
            ]);
        }

        $defaultType = ProviderStaffScope::defaultTypeFor($admin);
        $rawType = $request->query('type');
        $type = ($rawType !== null && $rawType !== '') ? (string) $rawType : $defaultType;

        $allowed = ProviderStaffScope::allTypes();
        if (! in_array($type, $allowed, true)) {
            $type = $defaultType;
        }

        if ($scope !== null && ! in_array($type, $scope, true)) {
            return redirect()->route('super-admin.providers.index', [
                'type' => $scope[0],
                'q' => $request->query('q'),
                'verified' => $request->query('verified'),
            ])->with('toast', [
                'type' => 'warning',
                'message' => 'تم عرض قطاعك المصرّح به فقط.',
            ]);
        }

        $q = trim((string) $request->query('q', ''));
        $verified = trim((string) $request->query('verified', ''));

        $profiles = ProviderProfile::query()
            ->with('user')
            ->where('provider_type', $type)
            ->when($q !== '', function ($query) use ($q) {
                $query->whereHas('user', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->when($verified !== '', function ($query) use ($verified) {
                if ($verified === 'yes') {
                    $query->where('is_verified', true);
                } elseif ($verified === 'no') {
                    $query->where('is_verified', false);
                }
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $typeLabel = match ($type) {
            'delivery' => 'الدليفري (جيبلي معك)',
            'taxi' => 'تكسي',
            'water_tanker' => 'صهاريج مياه',
            'workshop' => 'ورشات',
            default => 'الدليفري',
        };

        return view('super-admin.providers.index', [
            'type' => $type,
            'typeLabel' => $typeLabel,
            'q' => $q,
            'verified' => $verified,
            'profiles' => $profiles,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider_type' => ['required', 'in:delivery,taxi,water_tanker,workshop'],
            'mode' => ['required', 'in:existing,new'],
            'phone' => ['required', 'string', 'max:50'],
            'name' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6', 'max:100'],
            'commission_rate_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'monthly_deposit_amount' => ['required', 'numeric', 'min:0'],
            'deposit_period_months' => ['required', 'integer', 'min:1', 'max:36'],
        ]);

        $plainPasswordToShow = null;
        $user = User::query()->where('phone', $validated['phone'])->first();

        if ($validated['mode'] === 'existing') {
            if (! $user) {
                return back()->withInput()->withErrors(['phone' => 'لا يوجد مستخدم بهذا الرقم']);
            }
        } else {
            if ($user) {
                return back()->withInput()->withErrors(['phone' => 'رقم الجوال مستخدم مسبقاً، اختر “مستخدم موجود”']);
            }

            $plainPasswordToShow = $validated['password'] ?: (string) random_int(10000000, 99999999);

            $user = User::query()->create([
                'name' => $validated['name'] ?: 'مزود خدمة',
                'phone' => $validated['phone'],
                'password' => Hash::make($plainPasswordToShow),
                'role' => 'driver',
            ]);
        }

        DB::transaction(function () use ($validated, $user) {
            $depositStartsAt = now();
            $depositEndsAt = now()->copy()->addMonths((int) $validated['deposit_period_months']);

            ProviderProfile::query()->updateOrCreate(
                ['user_id' => $user->id, 'provider_type' => $validated['provider_type']],
                [
                    'full_name' => $user->name,
                    'commission_rate_percent' => $validated['commission_rate_percent'],
                    'monthly_deposit_amount' => $validated['monthly_deposit_amount'],
                    'deposit_period_months' => $validated['deposit_period_months'],
                    'deposit_starts_at' => $depositStartsAt,
                    'deposit_ends_at' => $depositEndsAt,
                    'deposit_status' => 'active',
                ]
            );
        });

        $profile = ProviderProfile::query()
            ->where('user_id', $user->id)
            ->where('provider_type', $validated['provider_type'])
            ->first();

        if ($profile) {
            $msg = 'تم إنشاء/تحديث ملف المزوّد. أكمل الآن بياناته.';
            if ($plainPasswordToShow) {
                $msg .= ' كلمة المرور المؤقتة: '.$plainPasswordToShow;
            }

            return redirect()->route('super-admin.providers.show', $profile)->with('toast', ['type' => 'success', 'message' => $msg]);
        }

        return redirect()->route('super-admin.providers.index', ['type' => $validated['provider_type']])
            ->with('toast', ['type' => 'success', 'message' => 'تم إنشاء/تحديث ملف المزوّد بنجاح']);
    }

    public function show(ProviderProfile $providerProfile)
    {
        $providerProfile->load('user');

        $typeLabel = match ($providerProfile->provider_type) {
            'delivery' => 'الدليفري (جيبلي معك)',
            'taxi' => 'تكسي',
            'water_tanker' => 'صهاريج مياه',
            'workshop' => 'ورشات',
            default => 'مزود خدمة',
        };

        return view('super-admin.providers.show', array_merge(
            ProviderProfileShowState::forProfile($providerProfile),
            [
                'typeLabel' => $typeLabel,
                'profile' => $providerProfile,
            ]
        ));
    }

    public function update(Request $request, ProviderProfile $providerProfile)
    {
        // Delivery is the first “complete” process. Other types can be expanded similarly.
        if ($providerProfile->provider_type === 'delivery') {
            $validated = $request->validate([
                'user_name' => ['required', 'string', 'max:255'],
                'user_phone' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('users', 'phone')->ignore($providerProfile->user_id),
                ],
                'full_name' => ['required', 'string', 'max:255'],
                'national_id' => ['nullable', 'string', 'max:50'],
                'license_no' => ['nullable', 'string', 'max:50'],
                'license_expiry' => ['nullable', 'date'],

                'vehicle_type' => ['required', 'in:motorcycle,bicycle,car,van,truck'],
                'vehicle_plate' => ['nullable', 'string', 'max:30'],
                'vehicle_color' => ['nullable', 'string', 'max:30'],

                'notes' => ['nullable', 'string', 'max:2000'],

                'id_document_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                'license_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                'vehicle_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);
        } elseif ($providerProfile->provider_type === 'taxi') {
            $allowedAreas = [
                'tafes',
                'daraa',
                'daraa_countryside',
                'damascus',
                'damascus_airport',
                'sy_jo_border',
                'sy_lb_border',
            ];

            $validated = $request->validate([
                'user_name' => ['required', 'string', 'max:255'],
                'user_phone' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('users', 'phone')->ignore($providerProfile->user_id),
                ],
                'full_name' => ['required', 'string', 'max:255'],

                'license_no' => ['nullable', 'string', 'max:50'],
                'license_expiry' => ['nullable', 'date'],

                'vehicle_type' => ['required', 'in:car,van'],
                'vehicle_plate' => ['required', 'string', 'max:30'],
                'vehicle_color' => ['nullable', 'string', 'max:30'],

                'taxi_car_make' => ['required', 'string', 'max:60'],
                'taxi_car_model' => ['required', 'string', 'max:60'],
                'taxi_car_year' => ['required', 'integer', 'min:1990', 'max:'.(int) now()->format('Y')],
                'taxi_seats' => ['required', 'integer', 'min:1', 'max:8'],

                'taxi_insurance_no' => ['nullable', 'string', 'max:60'],
                'taxi_insurance_expiry' => ['nullable', 'date'],

                'taxi_has_ac' => ['nullable', 'boolean'],
                'taxi_allows_smoking' => ['nullable', 'boolean'],

                'taxi_is_metered' => ['nullable', 'boolean'],
                'taxi_pricing_mode' => ['required', 'in:simple,meter'],
                'taxi_base_fare' => ['required', 'numeric', 'min:0'],
                'taxi_price_per_km' => ['required', 'numeric', 'min:0'],
                'taxi_price_per_minute' => ['nullable', 'numeric', 'min:0'],
                'taxi_min_fare' => ['required', 'numeric', 'min:0'],
                'taxi_coverage_area_keys' => ['required', 'array', 'min:1'],
                'taxi_coverage_area_keys.*' => ['string', Rule::in($allowedAreas)],

                'notes' => ['nullable', 'string', 'max:2000'],

                'id_document_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                'license_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                'vehicle_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);

            if (($validated['taxi_pricing_mode'] ?? 'simple') === 'simple') {
                $validated['taxi_price_per_minute'] = 0;
                $validated['taxi_is_metered'] = false;
            } else {
                $validated['taxi_is_metered'] = true;
            }
        } elseif ($providerProfile->provider_type === 'water_tanker') {
            $allowedAreas = [
                'tafes',
                'tafes_farms',
            ];

            $validated = $request->validate([
                'user_name' => ['required', 'string', 'max:255'],
                'user_phone' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('users', 'phone')->ignore($providerProfile->user_id),
                ],
                'full_name' => ['required', 'string', 'max:255'],
                'national_id' => ['nullable', 'string', 'max:50'],

                'water_capacity_liters' => ['required', 'integer', 'min:200', 'max:80000'],
                'water_has_pump' => ['nullable', 'boolean'],
                'water_hose_length_m' => ['nullable', 'integer', 'min:0', 'max:300'],
                'water_pricing_mode' => ['required', 'in:per_tank,per_liter'],
                'water_price_per_tank' => ['required_if:water_pricing_mode,per_tank', 'nullable', 'numeric', 'min:0'],
                'water_price_per_liter' => ['required_if:water_pricing_mode,per_liter', 'nullable', 'numeric', 'min:0'],
                'water_min_order_liters' => ['nullable', 'integer', 'min:0', 'max:80000'],
                'water_service_area_keys' => ['required', 'array', 'min:1'],
                'water_service_area_keys.*' => ['string', Rule::in($allowedAreas)],
                'water_potable_declared' => ['nullable', 'boolean'],

                'notes' => ['nullable', 'string', 'max:2000'],

                'id_document_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                'license_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                'vehicle_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);

            $validated['water_has_pump'] = $request->boolean('water_has_pump');
            $validated['water_potable_declared'] = $request->boolean('water_potable_declared');

            if (($validated['water_pricing_mode'] ?? '') === 'per_tank') {
                $validated['water_price_per_liter'] = null;
            } else {
                $validated['water_price_per_tank'] = null;
            }
        } elseif ($providerProfile->provider_type === 'workshop') {
            $allowedWorkshopKeys = WorkshopCategories::allowedKeys();

            $validated = $request->validate([
                'user_name' => ['required', 'string', 'max:255'],
                'user_phone' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('users', 'phone')->ignore($providerProfile->user_id),
                ],
                'full_name' => ['required', 'string', 'max:255'],
                'national_id' => ['nullable', 'string', 'max:50'],
                'license_no' => ['nullable', 'string', 'max:50'],
                'license_expiry' => ['nullable', 'date'],

                'workshop_category_keys' => ['required', 'array', 'min:1', 'max:15'],
                'workshop_category_keys.*' => ['string', Rule::in($allowedWorkshopKeys)],
                'workshop_skill_other' => [
                    'nullable',
                    'string',
                    'max:200',
                    Rule::requiredIf(function () use ($request) {
                        $keys = $request->input('workshop_category_keys', []);

                        return is_array($keys) && in_array('other_custom', $keys, true);
                    }),
                ],

                'workshop_neighborhood' => ['nullable', 'string', 'max:120'],
                'workshop_short_pitch' => ['nullable', 'string', 'max:280'],
                'workshop_years_experience' => ['nullable', 'integer', 'min:0', 'max:60'],
                'workshop_home_visit' => ['nullable', 'boolean'],

                'notes' => ['nullable', 'string', 'max:2000'],

                'id_document_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                'license_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                'vehicle_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);

            $validated['workshop_home_visit'] = $request->boolean('workshop_home_visit');
            $validated['workshop_category_keys'] = array_values(array_unique($validated['workshop_category_keys']));
        } else {
            $validated = $request->validate([
                'full_name' => ['nullable', 'string', 'max:255'],
                'notes' => ['nullable', 'string', 'max:2000'],
            ]);
        }

        DB::transaction(function () use ($request, $providerProfile, $validated) {
            if (in_array($providerProfile->provider_type, ['delivery', 'taxi', 'water_tanker', 'workshop'], true)) {
                $providerProfile->user()->update([
                    'name' => $validated['user_name'],
                    'phone' => $validated['user_phone'],
                ]);
            }

            $providerProfile->fill($validated);

            $baseDir = "providers/{$providerProfile->id}";

            foreach (['id_document_image', 'license_image', 'vehicle_image'] as $field) {
                if ($request->hasFile($field)) {
                    $path = $request->file($field)->storePublicly($baseDir, ['disk' => 'public']);
                    $providerProfile->{$field} = $path;
                }
            }

            $providerProfile->save();
        });

        return back()->with('toast', ['type' => 'success', 'message' => 'تم حفظ بيانات المزوّد بنجاح']);
    }

    public function verify(ProviderProfile $providerProfile)
    {
        $providerProfile->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        return back()->with('toast', ['type' => 'success', 'message' => 'تم توثيق المزوّد']);
    }

    public function unverify(ProviderProfile $providerProfile)
    {
        $providerProfile->update([
            'is_verified' => false,
            'verified_at' => null,
        ]);

        return back()->with('toast', ['type' => 'success', 'message' => 'تم إلغاء توثيق المزوّد']);
    }

    public function toggleActive(ProviderProfile $providerProfile)
    {
        $user = $providerProfile->user()->first();
        if ($user) {
            $user->is_active = ! (bool) $user->is_active;
            $user->save();
        }

        return back()->with('toast', ['type' => 'success', 'message' => 'تم تحديث حالة التفعيل']);
    }

    public function renewSubscription(Request $request, ProviderProfile $providerProfile)
    {
        $validated = $request->validate([
            'add_months' => ['required', 'integer', 'min:1', 'max:36'],
            'monthly_deposit_amount' => ['required', 'numeric', 'min:0'],
            'commission_rate_percent' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        DB::transaction(function () use ($providerProfile, $validated) {
            $startsAt = $providerProfile->deposit_starts_at ?? now();
            $currentEnd = $providerProfile->deposit_ends_at;

            $base = $currentEnd && $currentEnd->isFuture() ? $currentEnd->copy() : now();
            $newEnd = $base->addMonths((int) $validated['add_months']);

            $providerProfile->update([
                'commission_rate_percent' => $validated['commission_rate_percent'],
                'monthly_deposit_amount' => $validated['monthly_deposit_amount'],
                'deposit_period_months' => (int) $providerProfile->deposit_period_months + (int) $validated['add_months'],
                'deposit_starts_at' => $startsAt,
                'deposit_ends_at' => $newEnd,
                'deposit_status' => 'active',
            ]);
        });

        return back()->with('toast', ['type' => 'success', 'message' => 'تم تجديد الضمانة بنجاح']);
    }

    public function toggleSubscriptionPause(ProviderProfile $providerProfile)
    {
        $next = $providerProfile->deposit_status === 'paused' ? 'active' : 'paused';
        $providerProfile->update([
            'deposit_status' => $next,
        ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => $next === 'paused' ? 'تم إيقاف الضمانة مؤقتاً' : 'تم استئناف الضمانة',
        ]);
    }
}
