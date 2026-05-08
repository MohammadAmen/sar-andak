<?php

namespace App\Support;

use App\Models\SuperAdmin;

class ProviderStaffScope
{
    /**
     * @return list<string>
     */
    public static function allTypes(): array
    {
        return config('provider_ops.provider_types', []);
    }

    /**
     * @return list<string>|null null = غير مقيّد (كل الأنواع)
     */
    public static function allowedTypesFor(?SuperAdmin $admin): ?array
    {
        if (! $admin) {
            return null;
        }

        $scope = $admin->provider_scope;
        if ($scope === null) {
            return null;
        }

        if (! is_array($scope)) {
            return [];
        }

        $all = self::allTypes();

        return array_values(array_intersect($all, $scope));
    }

    public static function canAccessProviderType(?SuperAdmin $admin, string $type): bool
    {
        $allowed = self::allowedTypesFor($admin);
        if ($allowed === null) {
            return true;
        }

        return in_array($type, $allowed, true);
    }

    /**
     * أول نوع مسموح لاستخدامه في التوجيه الافتراضي.
     */
    public static function defaultTypeFor(?SuperAdmin $admin): string
    {
        $allowed = self::allowedTypesFor($admin);
        if ($allowed === null) {
            return 'delivery';
        }

        return $allowed[0] ?? 'delivery';
    }
}
