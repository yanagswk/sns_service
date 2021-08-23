<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tag;


class TagController extends Controller
{
    /**
     * タグ別記事一覧表示
     * @method GET
     * @param string $name タグ名
     */
    public function show(string $name)
    {
        $tag = Tag::where('name', $name)->first();
        return view('tags.show', ['tag' => $tag]);
    }

}
