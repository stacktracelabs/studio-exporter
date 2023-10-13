<?php

namespace App\Models;

use Fureev\Trees\Config\Base;
use Fureev\Trees\Contracts\TreeConfigurable;
use Fureev\Trees\NestedSetTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property string $title
 * @property string $slug
 * @property \Carbon\Carbon|null $published_at
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\StullerCategory> $stullerCategories
 * @property bool $is_link
 * @property bool $is_invisible
 * @property string|null $route_type
 * @property string|null $route_name
 * @property string|null $route_params
 * @property string|null $query_params
 * @property string|null $external_url
 * @property boolean $is_3c Enables 3C features for the collection.
 * @property boolean $hide_on_showcase Hides collection in showcases.
 * @property boolean $hide_in_studio Hides collection in the Studio.
 * @property boolean $hide_from_landing_list Hides collection from generated landing page of the collection.
 * @property string $path
 */
class ProductCollection extends Model implements HasMedia, TreeConfigurable
{
    use InteractsWithMedia, HasTranslations, NestedSetTrait {
        NestedSetTrait::getCasts as getNestedSetTraitCasts;
        HasTranslations::getCasts as getHasTranslationsCasts;
    }

    protected array $translatable = [
        'title',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_link' => 'boolean',
        'is_invisible' => 'boolean',
        'excluded_from_categorization' => 'boolean',
        'is_3c' => 'boolean',
        'hide_on_showcase' => 'boolean',
        'hide_in_studio' => 'boolean',
        'hide_from_landing_list' => 'boolean',
        'custom_properties' => 'array',
    ];

    public function parent(): BelongsTo
    {
        return parent::parent()->withTrashed();
    }

    public function stullerCategories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            StullerCategory::class,
            'stuller_product_collection_links',
            'product_collection_id',
            'stuller_category_id',
        );
    }

    public function isPublished(): bool
    {
        return $this->published_at != null;
    }

    public function hasTrashedParent(): bool
    {
        return $this->newQuery()->withTrashed()->parents(null)->whereNotNull('deleted_at')->exists();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('mainImage')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumbnail')
                    ->crop('crop-center', 260, 155);
            });
    }

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
        $url = $this->getFirstMedia('mainImage')?->getUrl();
        $image = null;

        if ($url) {
            $fileName = "collection_".$this->id.'_'.basename($url);

            $path = base_path("exports/assets/$fileName");

            if (! file_exists($path)) {
                file_put_contents($path, file_get_contents($url));
            }

            $image = $fileName;
        }

        return [
            'id' => $this->id,
            'title' => $this->getTranslations('title'),
            'slug' => $this->slug,
            'custom_properties' => $this->custom_properties,
            'display_type' => $this->display_type,
            'is_published' => $this->isPublished(),
            'note' => $this->note,
            'filter_mode' => $this->filter_mode,
            'route_type' => $this->route_type,
            'route_name' => $this->route_name,
            'route_params' => $this->route_params,
            'query_params' => $this->query_params,
            'external_url' => $this->external_url,
            'is_link' => $this->is_link,
            'is_invisible' => $this->is_invisible,
            'excluded_from_categorization' => $this->excluded_from_categorization,
            'hide_on_showcase' => $this->hide_on_showcase,
            'is_3c' => $this->is_3c,
            'hide_from_landing_list' => $this->hide_from_landing_list,
            'hide_in_studio' => $this->hide_in_studio,
            'links' => $this->stullerCategories->pluck('id')->all(),
            'image' => $image,
            'children' => $this->children->map->export()->all(),
        ];
    }
}
