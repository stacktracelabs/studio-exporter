<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $title
 * @property string|null $location
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\MegaMenuNavigation> $navigations
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\NavigationCardImage> $images
 */
class NavigationCard extends Model
{
    use SoftDeletes, HasTranslations;

    protected $table = 'mm_navigation_cards';

    protected $guarded = false;

    protected array $translatable = [
        'title', 'location',
    ];

    public function navigations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MegaMenuNavigation::class, 'card_id');
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NavigationCardImage::class, 'card_id');
    }

    public function productCard(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProductCard::class, 'card_id');
    }

    public function export(): array
    {
        return [
            'title' => $this->getTranslations('title'),
            'location' => $this->getTranslations('location'),
            'location_type' => $this->location_type,
            'route_type' => $this->route_type,
            'route_name' => $this->route_name,
            'route_params' => $this->route_params,
            'query_params' => $this->query_params,
            'external_url' => $this->external_url,
            'navigations' => $this->navigations->map(function (MegaMenuNavigation $navigation) {
                return [
                    'title' => $navigation->getTranslations('title'),
                    'col' => $navigation->col,
                    'sortable_as' => $navigation->sortable_as,
                    'is_invisible' => $navigation->is_invisible,
                    'items' => $navigation->items->map->export()->all()
                ];
            })->all(),
            'images' => $this->images->map(function (NavigationCardImage $image) {
                $url = Str::replace('test.zlatnickestudio.sk', 'www.zlatnickestudio.sk', $image->image_url);

                $fileName = "nci_".$image->id.'_'.basename($url);

                $path = base_path("exports/assets/$fileName");

                if (! file_exists($path)) {
                    try {
                        file_put_contents($path, file_get_contents($url));
                    } catch (\Throwable) {
                        return null;
                    }
                }

                return $fileName;
            })->filter()->values()->all(),
        ];
    }
}
