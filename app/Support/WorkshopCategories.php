<?php

namespace App\Support;

class WorkshopCategories
{
    /**
     * @return array<string, array{label: string, icon: string, items: array<string, array{label: string, icon: string}>}>
     */
    public static function groups(): array
    {
        return config('workshop_categories.groups', []);
    }

    /**
     * @return list<string>
     */
    public static function allowedKeys(): array
    {
        $keys = [];
        foreach (self::groups() as $group) {
            foreach (array_keys($group['items'] ?? []) as $k) {
                $keys[] = $k;
            }
        }

        return $keys;
    }

    public static function label(string $key): ?string
    {
        foreach (self::groups() as $group) {
            if (isset($group['items'][$key]['label'])) {
                return $group['items'][$key]['label'];
            }
        }

        return null;
    }
}
