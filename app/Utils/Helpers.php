<?php

use Illuminate\Support\Collection;

if (!function_exists('find_child')) {
    function find_child($root, $id)
    {
        if ($root['id'] == $id) return $root;
        if (isset($root['children'])) {
            foreach ($root['children'] as $node) {
                $found = find_child($node, $id);
                if ($found) return $found;
            }
        }
        return null;
    }
}

if (!function_exists('format_permissions')) {
    function format_permissions(Collection $collection): Collection
    {
        return $collection->map(function($item) {
            return [
                'name' => $item['name'],
                'translation' => __($item['name'])
            ];
        });
    }
}

if (!function_exists('is_subset')) {
    function is_subset($subset, $set): bool
    {
        return empty(array_diff($subset, $set));
    }
}
