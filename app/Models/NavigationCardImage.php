<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

/**
 * @property string|null $title
 * @property \App\Models\NavigationCard $card
 * @property \App\Models\NavigationItem|null $action
 * @property string $image_url
 */
class NavigationCardImage extends Model
{
    use SoftDeletes, HasTranslations;

    protected $table = 'mm_card_images';

    protected $guarded = false;

    protected array $translatable = [
        'title',
    ];

    public function card(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(NavigationCard::class, 'card_id');
    }

    public function action(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(NavigationItem::class, 'action_id');
    }
}
