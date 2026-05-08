<?php

/**
 * إعدادات لوحة تشغيل المزوّدين (سوبر أدمن / موظفو قطاع).
 *
 * provider_scope في جدول super_admins:
 * - null          ⇒ وصول لكل الأنواع (سوبر أدمن كامل).
 * - ['water_tanker', 'taxi'] ⇒ يُسمح فقط بهذه الأنواع (موظف قطاع).
 */
return [
    'provider_types' => ['delivery', 'taxi', 'water_tanker', 'workshop'],

    'nav' => [
        'delivery' => [
            'label' => 'الدليفري',
            'icon' => 'bi-truck',
        ],
        'taxi' => [
            'label' => 'تكسي',
            'icon' => 'bi-car-front',
        ],
        'water_tanker' => [
            'label' => 'صهاريج مياه',
            'icon' => 'bi-droplet-half',
        ],
        'workshop' => [
            'label' => 'ورشات',
            'icon' => 'bi-tools',
        ],
    ],
];
