<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\Article;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];


    /**
     * タグの先頭に「#」をつける
     * アクセサ使用
     * tag->hashtagで取得
     */
    public function getHashtagAttribute(): string
    {
        return '#' . $this->name;
    }


    /**
     * articleテーブルとのリレーション
     *
     * article_tag: tagテーブルとの中間テーブル
     * belongsToMany('role_user', 中間テーブル)
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_tag');
    }
}
