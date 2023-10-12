<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string|null $description
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\NavigationCard> $cards
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\ProductCard> $productCards
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\QuickLink> $quickLinks
 */
class MegaMenu extends Model
{
    use SoftDeletes;

    protected $table = 'mega_menus';

    protected $guarded = false;

    public function cards(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            NavigationCard::class,
            'mega_menu_mm_navigation_card',
            'mega_menu_id',
            'navigation_card_id',
        );
    }

    public function productCards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductCard::class, 'mega_menu_id');
    }

    public function quickLinks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(QuickLink::class, 'menu_id');
    }
}
