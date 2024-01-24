<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Entity extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'parent_id', 'id');
    }

    public function scopeChildren(Builder $query, $includeSelf = true): Builder
    {
        $id = $this->getAttribute('id');
        $ids = DB::query()
            ->select(DB::raw("id FROM (select id, parent_id FROM $this->table ORDER BY parent_id, id) $this->table, (SELECT @pv := {$id}) initialisation WHERE find_in_set(parent_id, @pv) > 0 and @pv := concat(@pv, ',', id);"))
            ->get()
            ->pluck('id')
            ->toArray();
        if ($includeSelf) {
            $ids[] = $id;
        }
        asort($ids);
        return $query->whereIn('id', $ids);
    }

    public function scopeTree(Builder $query): Collection
    {
        $children = $query->get();
        return collect(self::buildTree($children->toArray(), $this->getAttribute('parent_id')));
    }

    public static function buildTree($elements, $parentId = 0): array
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = self::buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
}
