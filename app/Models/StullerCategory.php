<?php

namespace App\Models;

use Fureev\Trees\Config\Base;
use Fureev\Trees\Contracts\TreeConfigurable;
use Fureev\Trees\NestedSetTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $stuller_id
 * @property string $name
 * @property string $slug
 * @property string $image_url
 * @method static \Fureev\Trees\QueryBuilder query()
 */
class StullerCategory extends Model implements TreeConfigurable
{
    use SoftDeletes, NestedSetTrait;

    protected $guarded = false;

    protected $casts = [
        'is_ignored' => 'boolean',
    ];

    public function collections(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            ProductCollection::class,
            'stuller_product_collection_links',

            'stuller_category_id',
            'product_collection_id',
        );
    }

    protected static function buildTreeConfig(): Base
    {
        return new Base(multi: true);
    }

    public function export(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'original_image_url' => $this->image_url,
            'path' => $this->full_path,
            'is_ignored' => $this->is_ignored,
            'note' => $this->note,
            'stuller_id' => $this->stuller_id,
            'children' => $this->children->map->export()->all(),
        ];
    }
}
