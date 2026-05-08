<?php

namespace App\Support;

use App\Models\ProviderProfile;
use Illuminate\Support\MessageBag;

class ProviderProfileShowState
{
    /**
     * @return array<string, mixed>
     */
    public static function forProfile(ProviderProfile $profile): array
    {
        $remainingDays = null;
        if ($profile->deposit_ends_at) {
            $remainingDays = now()->startOfDay()->diffInDays($profile->deposit_ends_at->copy()->startOfDay(), false);
        }

        $subscriptionStatusLabel = match ($profile->deposit_status) {
            'active' => 'نشط',
            'paused' => 'موقوف مؤقتاً',
            'expired' => 'منتهي',
            default => '-',
        };

        $coverageRegionOptions = [
            'tafes' => ['label' => 'طفس', 'icon' => 'bi-geo-alt-fill'],
            'daraa' => ['label' => 'درعا', 'icon' => 'bi-pin-map-fill'],
            'daraa_countryside' => ['label' => 'أرياف درعا', 'icon' => 'bi-tree-fill'],
            'damascus' => ['label' => 'دمشق', 'icon' => 'bi-building'],
            'damascus_airport' => ['label' => 'مطار دمشق', 'icon' => 'bi-airplane'],
            'sy_jo_border' => ['label' => 'الحدود السورية الأردنية', 'icon' => 'bi-signpost-split-fill'],
            'sy_lb_border' => ['label' => 'الحدود السورية اللبنانية', 'icon' => 'bi-signpost-split'],
        ];

        $taxiCoverageSelected = [];
        if (($profile->provider_type ?? null) === 'taxi') {
            $covRaw = old('taxi_coverage_area_keys', $profile->taxi_coverage_area_keys ?? []);
            $taxiCoverageSelected = is_array($covRaw) ? $covRaw : [];
        }

        $waterCoverageRegionOptions = [
            'tafes' => ['label' => 'طفس', 'icon' => 'bi-geo-alt-fill'],
            'tafes_farms' => ['label' => 'المزارع المحيطة بطفس', 'icon' => 'bi-tree-fill'],
        ];

        $waterCoverageSelected = [];
        if (($profile->provider_type ?? null) === 'water_tanker') {
            $wRaw = old('water_service_area_keys', $profile->water_service_area_keys ?? []);
            $waterCoverageSelected = is_array($wRaw) ? $wRaw : [];
        }

        $errBag = session('errors');
        $hasFormErrors = $errBag instanceof MessageBag && $errBag->isNotEmpty();

        $taxiActivePane = 'taxi-pane-driver';
        if (($profile->provider_type ?? null) === 'taxi' && $hasFormErrors) {
            $taxiPaneByField = [
                'user_name' => 'taxi-pane-driver',
                'user_phone' => 'taxi-pane-driver',
                'full_name' => 'taxi-pane-driver',
                'license_no' => 'taxi-pane-driver',
                'license_expiry' => 'taxi-pane-driver',
                'vehicle_type' => 'taxi-pane-vehicle',
                'vehicle_plate' => 'taxi-pane-vehicle',
                'vehicle_color' => 'taxi-pane-vehicle',
                'taxi_car_make' => 'taxi-pane-vehicle',
                'taxi_car_model' => 'taxi-pane-vehicle',
                'taxi_car_year' => 'taxi-pane-vehicle',
                'taxi_seats' => 'taxi-pane-vehicle',
                'taxi_insurance_no' => 'taxi-pane-vehicle',
                'taxi_insurance_expiry' => 'taxi-pane-vehicle',
                'taxi_has_ac' => 'taxi-pane-vehicle',
                'taxi_allows_smoking' => 'taxi-pane-vehicle',
                'taxi_pricing_mode' => 'taxi-pane-pricing',
                'taxi_base_fare' => 'taxi-pane-pricing',
                'taxi_min_fare' => 'taxi-pane-pricing',
                'taxi_price_per_km' => 'taxi-pane-pricing',
                'taxi_price_per_minute' => 'taxi-pane-pricing',
                'taxi_coverage_area_keys' => 'taxi-pane-pricing',
                'notes' => 'taxi-pane-pricing',
                'id_document_image' => 'taxi-pane-docs',
                'license_image' => 'taxi-pane-docs',
                'vehicle_image' => 'taxi-pane-docs',
            ];
            foreach ($errBag->keys() as $ek) {
                $base = explode('.', (string) $ek, 2)[0];
                if (isset($taxiPaneByField[$base])) {
                    $taxiActivePane = $taxiPaneByField[$base];
                    break;
                }
            }
        }

        $waterActivePane = 'water-pane-identity';
        if (($profile->provider_type ?? null) === 'water_tanker' && $hasFormErrors) {
            $waterPaneByField = [
                'user_name' => 'water-pane-identity',
                'user_phone' => 'water-pane-identity',
                'full_name' => 'water-pane-identity',
                'national_id' => 'water-pane-identity',
                'water_capacity_liters' => 'water-pane-tank',
                'water_has_pump' => 'water-pane-tank',
                'water_hose_length_m' => 'water-pane-tank',
                'water_potable_declared' => 'water-pane-tank',
                'water_pricing_mode' => 'water-pane-service',
                'water_price_per_tank' => 'water-pane-service',
                'water_price_per_liter' => 'water-pane-service',
                'water_min_order_liters' => 'water-pane-service',
                'water_service_area_keys' => 'water-pane-service',
                'notes' => 'water-pane-service',
                'id_document_image' => 'water-pane-docs',
                'license_image' => 'water-pane-docs',
                'vehicle_image' => 'water-pane-docs',
            ];
            foreach ($errBag->keys() as $ek) {
                $base = explode('.', (string) $ek, 2)[0];
                if (isset($waterPaneByField[$base])) {
                    $waterActivePane = $waterPaneByField[$base];
                    break;
                }
            }
        }

        $workshopCategoryGroups = WorkshopCategories::groups();
        $workshopCategorySelected = [];
        if (($profile->provider_type ?? null) === 'workshop') {
            $wkRaw = old('workshop_category_keys', $profile->workshop_category_keys ?? []);
            $workshopCategorySelected = is_array($wkRaw) ? $wkRaw : [];
        }

        $workshopActivePane = 'workshop-pane-identity';
        if (($profile->provider_type ?? null) === 'workshop' && $hasFormErrors) {
            $workshopPaneByField = [
                'user_name' => 'workshop-pane-identity',
                'user_phone' => 'workshop-pane-identity',
                'full_name' => 'workshop-pane-identity',
                'national_id' => 'workshop-pane-identity',
                'license_no' => 'workshop-pane-identity',
                'license_expiry' => 'workshop-pane-identity',
                'workshop_category_keys' => 'workshop-pane-cats',
                'workshop_skill_other' => 'workshop-pane-cats',
                'workshop_neighborhood' => 'workshop-pane-search',
                'workshop_short_pitch' => 'workshop-pane-search',
                'workshop_years_experience' => 'workshop-pane-search',
                'workshop_home_visit' => 'workshop-pane-search',
                'notes' => 'workshop-pane-search',
                'id_document_image' => 'workshop-pane-docs',
                'license_image' => 'workshop-pane-docs',
                'vehicle_image' => 'workshop-pane-docs',
            ];
            foreach ($errBag->keys() as $ek) {
                $base = explode('.', (string) $ek, 2)[0];
                if (isset($workshopPaneByField[$base])) {
                    $workshopActivePane = $workshopPaneByField[$base];
                    break;
                }
            }
        }

        return [
            'remainingDays' => $remainingDays,
            'subscriptionStatusLabel' => $subscriptionStatusLabel,
            'coverageRegionOptions' => $coverageRegionOptions,
            'taxiCoverageSelected' => $taxiCoverageSelected,
            'waterCoverageRegionOptions' => $waterCoverageRegionOptions,
            'waterCoverageSelected' => $waterCoverageSelected,
            'taxiActivePane' => $taxiActivePane,
            'waterActivePane' => $waterActivePane,
            'workshopCategoryGroups' => $workshopCategoryGroups,
            'workshopCategorySelected' => $workshopCategorySelected,
            'workshopActivePane' => $workshopActivePane,
        ];
    }
}
