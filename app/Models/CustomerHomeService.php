<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerHomeService extends Model
{
    public const ICON_KEYS = [
        'send' => 'طلب / إرسال',
        'car' => 'مركبة (تكسي)',
        'droplets' => 'مياه / صهريج',
        'hammer' => 'ورشة / صيانة',
    ];

    public const ACCENT_KEYS = [
        'blue' => 'أزرق',
        'rose' => 'وردي',
        'cyan' => 'سماوي',
        'emerald' => 'أخضر زمردي',
        'amber' => 'كهرماني',
        'violet' => 'بنفسجي',
        'orange' => 'برتقالي',
    ];

    protected $fillable = [
        'slug',
        'title',
        'subtitle',
        'icon_key',
        'accent_key',
        'route_segment',
        'sort_order',
        'is_enabled',
        'badge_label',
        'disabled_message',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
