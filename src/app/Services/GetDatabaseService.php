<?php

namespace App\Services;

use App\Models\Tag;


class GetDatabaseService
{
    /**
     * 全てのタグ名を返す。
     */
    public static function getAllTagNames()
    {
        return Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });
    }
}


?>
