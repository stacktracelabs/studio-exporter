<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property \App\Models\NavigationCard $card
 * @property \App\Models\MegaMenu $megaMenu
 */
class ProductCard extends Model
{
    use SoftDeletes;

    protected $table = 'mm_product_cards';

    protected $guarded = false;

    public function card(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(NavigationCard::class, 'card_id');
    }

    public function megaMenu(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MegaMenu::class, 'mega_menu_id');
    }
}
