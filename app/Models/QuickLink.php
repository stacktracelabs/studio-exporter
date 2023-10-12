<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $sortable_as
 * @property \App\Models\NavigationItem $navigationItem
 */
class QuickLink extends Model
{
    use SoftDeletes;

    protected $table = 'mm_quick_links';

    protected $guarded = false;

    public function navigationItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(NavigationItem::class, 'item_id');
    }

    public function export(): array
    {
        return [
            'sortable_as' => $this->sortable_as,
            'item' => $this->navigationItem->export(),
        ];
    }
}
