<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $title
 * @property string $location
 * @property NavigationItemType $type
 * @property string|null $badge
 * @property \App\Models\MegaMenuNavigation|null $navigation
 * @property int $sortable_as
 */
class NavigationItem extends Model
{
    use SoftDeletes, HasTranslations;

    protected $table = 'mm_navigation_items';

    protected $guarded = false;

    protected array $translatable = [
        'title', 'location', 'badge',
    ];

    public function navigation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MegaMenuNavigation::class, 'navigation_id');
    }

    public function export(): array
    {
        return [
            'title' => $this->getTranslations('title'),
            'location' => $this->getTranslations('location'),
            'type' => $this->type,
            'badge' => $this->getTranslations('badge'),
            'route_type' => $this->route_type,
            'route_name' => $this->route_name,
            'route_params' => $this->route_params,
            'sortable_as' => $this->sortable_as,
            'query_params' => $this->query_params,
            'external_url' => $this->external_url,
        ];
    }
}
