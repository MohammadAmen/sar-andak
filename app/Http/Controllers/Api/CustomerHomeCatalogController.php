<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerHomeService;
use Illuminate\Http\JsonResponse;

class CustomerHomeCatalogController extends Controller
{
    /**
     * قائمة بطاقات الخدمات للصفحة الرئيسية (عامّة، بدون مصادقة).
     */
    public function index(): JsonResponse
    {
        $services = CustomerHomeService::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(static function (CustomerHomeService $row) {
                return [
                    'slug' => $row->slug,
                    'title' => $row->title,
                    'subtitle' => $row->subtitle,
                    'icon_key' => $row->icon_key,
                    'accent_key' => $row->accent_key,
                    'route_segment' => $row->route_segment,
                    'is_enabled' => $row->is_enabled,
                    'badge_label' => $row->badge_label,
                    'disabled_message' => $row->disabled_message,
                ];
            });

        return response()->json([
            'services' => $services,
        ]);
    }
}
