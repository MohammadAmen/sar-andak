<?php

/**
 * تصنيفات ورش الخدمات المنزلية — مناسبة لطفس ودرعا وأريافها.
 * المفاتيح ثابتة للاستخدام في API والبحث لاحقاً.
 */
return [
    'groups' => [
        'farming' => [
            'label' => 'زراعة وبستنة',
            'icon' => 'bi-tree',
            'items' => [
                'ag_irrigation' => ['label' => 'ريّ ونوازع وشبكات ري', 'icon' => 'bi-droplet'],
                'ag_greenhouse' => ['label' => 'بيوت محمية وزراعة محمية', 'icon' => 'bi-house-door'],
                'ag_spraying' => ['label' => 'رشّ مبيدات ووقاية أشجار', 'icon' => 'bi-cloud-fog2'],
                'ag_pruning' => ['label' => 'تقليم أشجار وكروم وزيتون', 'icon' => 'bi-scissors'],
                'ag_harvest' => ['label' => 'مساعدة قطاف وعمال موسمية', 'icon' => 'bi-basket2'],
                'ag_soil' => ['label' => 'تجهيز أراضي وحراثة خفيفة', 'icon' => 'bi-layers'],
                'ag_fertilizer' => ['label' => 'تسميد وبرامج تغذية النباتات', 'icon' => 'bi-flower1'],
            ],
        ],
        'building' => [
            'label' => 'بناء وتشطيب',
            'icon' => 'bi-bricks',
            'items' => [
                'build_masonry' => ['label' => 'بناء وقصارة ولبّ طابوق', 'icon' => 'bi-grid-3x3'],
                'build_plaster' => ['label' => 'جبس ومعجون ومحارة', 'icon' => 'bi-square'],
                'build_waterproof' => ['label' => 'عزل أسطح وخزانات', 'icon' => 'bi-shield-check'],
                'build_tiles' => ['label' => 'بلاط وسيراميك وأرضيات', 'icon' => 'bi-grid-1x2'],
                'build_demolition' => ['label' => 'هدم وترميم جزئي', 'icon' => 'bi-hammer'],
                'build_extension' => ['label' => 'إضافات وملحقات', 'icon' => 'bi-building-add'],
            ],
        ],
        'electrical' => [
            'label' => 'كهرباء وإنارة',
            'icon' => 'bi-lightning-charge',
            'items' => [
                'elec_wiring' => ['label' => 'تمديدات كهرباء منازل', 'icon' => 'bi-outlet'],
                'elec_panel' => ['label' => 'لوحات كهرباء وتثبيت قواطع', 'icon' => 'bi-cpu'],
                'elec_lighting' => ['label' => 'إنارة LED داخلية وخارجية', 'icon' => 'bi-lightbulb'],
                'elec_solar' => ['label' => 'طاقة شمسية منزلية (تركيب بسيط)', 'icon' => 'bi-sun'],
                'elec_doorbell' => ['label' => 'أجراس وأقواس كاميرات منزلية', 'icon' => 'bi-bell'],
            ],
        ],
        'plumbing' => [
            'label' => 'سباكة ومياه وحرارة',
            'icon' => 'bi-water',
            'items' => [
                'plumb_general' => ['label' => 'سباكة منازل وتمديدات', 'icon' => 'bi-droplet-half'],
                'plumb_heater' => ['label' => 'سخانات غاز/بويلرات', 'icon' => 'bi-fire'],
                'plumb_tank' => ['label' => 'صيانة وتركيب علّاقات مياه', 'icon' => 'bi-database'],
                'plumb_pump' => ['label' => 'مضخات مياه منزلية', 'icon' => 'bi-gear-wide'],
                'plumb_leak' => ['label' => 'تسربات وإغلاق محابس', 'icon' => 'bi-droplet'],
            ],
        ],
        'carpentry' => [
            'label' => 'نجارة وألمنيوم',
            'icon' => 'bi-hammer',
            'items' => [
                'carp_wood' => ['label' => 'نجارة أخشاب وأبواب ونوافذ', 'icon' => 'bi-door-open'],
                'carp_furniture' => ['label' => 'تفصيل وتركيب غرف وخزائن', 'icon' => 'bi-box'],
                'carp_aluminum' => ['label' => 'ألمنيوم وشتر وواجهات زجاج', 'icon' => 'bi-layout-sidebar'],
                'carp_kitchen' => ['label' => 'مطابخ تفصيل وتركيب', 'icon' => 'bi-cup-hot'],
            ],
        ],
        'paint_deco' => [
            'label' => 'دهان وديكور',
            'icon' => 'bi-palette',
            'items' => [
                'paint_interior' => ['label' => 'دهان داخلي', 'icon' => 'bi-paint-bucket'],
                'paint_facade' => ['label' => 'دهان واجهات وعزل مائي للجدران', 'icon' => 'bi-building'],
                'deco_wallpaper' => ['label' => 'ورق جدران وكسوات', 'icon' => 'bi-image'],
                'deco_gypsum' => ['label' => 'جبس بورد وأسقف معلّقة', 'icon' => 'bi-square-half'],
            ],
        ],
        'cleaning' => [
            'label' => 'تنظيف وصيانة',
            'icon' => 'bi-stars',
            'items' => [
                'clean_home' => ['label' => 'تنظيف منازل عميق', 'icon' => 'bi-house-heart'],
                'clean_tank' => ['label' => 'غسل وتعقيم خزانات مياه', 'icon' => 'bi-droplet'],
                'clean_septic' => ['label' => 'تنضيد صرف صحي ومجاري', 'icon' => 'bi-arrow-down-circle'],
                'clean_ac' => ['label' => 'غسيل وصيانة مكيفات سبليت', 'icon' => 'bi-wind'],
                'clean_pest' => ['label' => 'مكافحة حشرات منزلية', 'icon' => 'bi-bug'],
            ],
        ],
        'appliance' => [
            'label' => 'أجهزة منزلية وإلكترونيات',
            'icon' => 'bi-plug',
            'items' => [
                'app_wash' => ['label' => 'صيانة غسالات', 'icon' => 'bi-droplet'],
                'app_fridge' => ['label' => 'صيانة ثلاجات وفريزرات', 'icon' => 'bi-snow'],
                'app_small' => ['label' => 'أفران ميكروويف وسخانات كهرباء', 'icon' => 'bi-lightning'],
                'app_tv' => ['label' => 'تركيب ستالايت وشاشات وتوصيلات', 'icon' => 'bi-tv'],
            ],
        ],
        'metal_auto' => [
            'label' => 'حدادة خفيفة وإطارات',
            'icon' => 'bi-wrench',
            'items' => [
                'metal_weld' => ['label' => 'لحام وحدادة منزلية خفيفة', 'icon' => 'bi-tools'],
                'auto_tire' => ['label' => 'برادي وتوازن إطارات', 'icon' => 'bi-record-circle'],
                'auto_batt' => ['label' => 'بطاريات واشتراك مركبات', 'icon' => 'bi-battery-charging'],
            ],
        ],
        'other' => [
            'label' => 'أخرى',
            'icon' => 'bi-three-dots',
            'items' => [
                'other_custom' => ['label' => 'تصنيف آخر (حدّد في الملاحظات)', 'icon' => 'bi-pencil-square'],
            ],
        ],
    ],
];
