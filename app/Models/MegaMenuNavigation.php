<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $title
 * @property int $col
 * @property int $sortable_as
 * @property \App\Models\NavigationCard $card
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\NavigationItem> $items
 */
class MegaMenuNavigation extends Model
{
    use SoftDeletes, HasTranslations;

    protected $table = 'mm_navigations';

    protected $guarded = false;

    protected $casts = [
        'is_invisible' => 'boolean',
    ];

    protected array $translatable = [
        'title',
    ];

    public function card(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(NavigationCard::class, 'card_id');
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NavigationItem::class, 'navigation_id');
    }
}
