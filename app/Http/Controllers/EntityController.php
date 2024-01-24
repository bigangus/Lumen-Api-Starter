<?php

namespace App\Http\Controllers;

use App\Http\Responses\Facade\HttpResponse;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class EntityController extends Controller
{
    public function list(Request $request): Response
    {
        $ids = Entity::query()->find(Auth::user()->getAttribute('entity_id'))->children()->pluck('id')->toArray();
        $entities = Entity::with('users')->whereIn('id', $ids)->get();
        $tree = Entity::buildTree($entities->toArray(), Auth::user()->entity->getAttribute('parent_id'));

        if ($request->has('id')) {
            $tree[0] = find_child($tree[0], $request->get('id'));
        }

        return HttpResponse::success('Success', [
            'entities' => $tree
        ]);
    }
}
