<?php

namespace App\Services;

use App\Models\Article;


interface ArticlesInterface
{
    /**
     * Articleモデルとのリレーション
     */
    public function articles();
}


?>
