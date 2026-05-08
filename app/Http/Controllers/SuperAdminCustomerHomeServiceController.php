<?php

namespace App\Http\Controllers;

use App\Models\CustomerHomeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SuperAdminCustomerHomeServiceController extends Controller
{
    public function index(): View
    {
        $services = CustomerHomeService::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('super-admin.home-services.index', [
            'services' => $services,
            'iconChoices' => CustomerHomeService::ICON_KEYS,
            'accentChoices' => CustomerHomeService::ACCENT_KEYS,
        ]);
    }

    public function update(Request $request, CustomerHomeService $homeService): RedirectResponse
    {
        $iconKeys = array_keys(CustomerHomeService::ICON_KEYS);
        $accentKeys = array_keys(CustomerHomeService::ACCENT_KEYS);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'subtitle' => ['required', 'string', 'max:500'],
            'icon_key' => ['required', 'string', Rule::in($iconKeys)],
            'accent_key' => ['required', 'string', Rule::in($accentKeys)],
            'route_segment' => ['required', 'string', 'max:120', 'regex:#^/services/[a-z0-9\-]+$#'],
            'is_enabled' => ['required', 'in:0,1'],
            'badge_label' => ['nullable', 'string', 'max:40'],
            'disabled_message' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['is_enabled'] = (bool) (int) $validated['is_enabled'];

        $homeService->fill($validated);
        $homeService->save();

        return redirect()
            ->route('super-admin.home-services.index')
            ->with('status', 'تم حفظ «'.$homeService->title.'».');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id' => ['required', 'integer', 'exists:customer_home_services,id'],
            'direction' => ['required', Rule::in(['up', 'down'])],
        ]);

        $service = CustomerHomeService::query()->findOrFail($validated['id']);

        $neighbor = CustomerHomeService::query()
            ->where(
                'sort_order',
                $validated['direction'] === 'up' ? '<' : '>',
                $service->sort_order
            )
            ->orderBy('sort_order', $validated['direction'] === 'up' ? 'desc' : 'asc')
            ->first();

        if ($neighbor) {
            DB::transaction(function () use ($service, $neighbor) {
                $a = $service->sort_order;
                $b = $neighbor->sort_order;
                $service->sort_order = $b;
                $neighbor->sort_order = $a;
                $service->save();
                $neighbor->save();
            });
        }

        return redirect()
            ->route('super-admin.home-services.index')
            ->with('status', 'تم تحديث ترتيب البطاقات.');
    }
}
