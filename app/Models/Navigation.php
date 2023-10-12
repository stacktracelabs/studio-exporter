<?php

namespace App\Models;

use Fureev\Trees\Config\Base;
use Fureev\Trees\Contracts\TreeConfigurable;
use Fureev\Trees\NestedSetTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

/**
 * @property string|null $name
 * @property string|null $title
 * @property string|null $location
 * @property string|null $route_name
 * @property int $sortable_as
 * @property array|null $route_params
 * @property array|null $custom_properties
 */
class Navigation extends Model implements TreeConfigurable
{
    use SoftDeletes, NestedSetTrait, HasTranslations {
        NestedSetTrait::getCasts as getNestedSetTraitCasts;
        HasTranslations::getCasts as getHasTranslationsCasts;
    }

    protected $guarded = false;

    protected array $translatable = [
        'title', 'location',
    ];

    protected $casts = [
        'route_params' => 'array',
        'custom_properties' => 'array',
    ];

    protected static function buildTreeConfig(): Base
    {
        return new Base(multi: true);
    }

    public function getCasts(): array
    {
        return array_merge(
            parent::getCasts(),
            $this->getNestedSetTraitCasts(),
            $this->getHasTranslationsCasts(),
        );
    }

    public function export(): array
    {
        return [
            'name' => $this->name,
            'title' => $this->getTranslations('title'),
            'location' => $this->getTranslations('location'),
            'location_type' => $this->location_type,
            'route_name' => $this->route_name,
            'route_params' => $this->route_params,
            'route_type' => $this->route_type,
            'query_params' => $this->query_params,
            'external_url' => $this->external_url,
            'sortable_as' => $this->sortable_as,
            'custom_properties' => $this->custom_properties,
            'children' => $this->children->isNotEmpty() ? $this->children()->get()->map->export()->all() : [],
        ];
    }
}
