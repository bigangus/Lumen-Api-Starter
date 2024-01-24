<?php

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
