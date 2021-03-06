<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body'
    ];

    /**
     * userモデルとのリレーション
     * 1(user)対多(article)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');

        // $article->user;         //-- Userモデルのインスタンスが返る
        // $article->user->name;   //-- Userモデルのインスタンスのnameプロパティの値が返る
        // $article->user->hoge(); //-- Userモデルのインスタンスのhogeメソッドの戻り値が返る
        // $article->user();       //-- BelongsToクラスのインスタンスが返る
    }


    /**
     *
     * 「いいね」における記事モデルとユーザーモデルの関係は多対多
     * likes:userテーブルとの中間テーブル
     * belongsToMany('role_user', 中間テーブル)
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }


    /**
     * ユーザーモデルを渡すと、そのユーザーがこの記事をいいね済みかどうかを返す
     *
     */
    public function isLikedBy(?User $user): bool
    {
        // dd($this->likes);
        return $user
            ? (bool)$this->likes->where('id', $user->id)->count()
            : false;
    }


    /**
     * 記事のいいね数を返す
     *
     * アクセサ使用
     * article->countlikesで呼べる
     */
    public function getCountLikesAttribute(): int
    {
        return $this->likes()->count();
    }


    /**
     * tagテーブルとのリレーション
     *
     * article_tag: tagテーブルとの中間テーブル
     * belongsToMany('role_user', 中間テーブル)
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag')->withTimestamps();
    }
}
