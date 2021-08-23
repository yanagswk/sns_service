<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\Auth;

use App\Models\Article;
use App\Models\Tag;


class ArticleController extends Controller
{

    public function __construct()
    {
        // ポリシーの判定を設定 (ArticlePolicy)
        $this->authorizeResource(Article::class, 'article');
    }


    /**
     * 一覧表示
     * @method GET
     */
    public function index()
    {
        // loadメソッドに引数としてリレーション名を渡すと、
        // リレーション先のテーブルからもデータを取得する。
        $articles = Article::all()->sortByDesc('created_at')
            ->load(['user', 'likes', 'tags']);

        return view('articles.index', ['articles' => $articles]);
    }


    /**
     *  記事投稿画面
     * @method GET
     */
    public function create()
    {
        // 全てのタグ名を取得
        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return view('articles.create', [
            'allTagNames' => $allTagNames
        ]);
    }


    /**
     * 登録処理
     * @method POST
     */
    public function store(ArticleRequest $request, Article $article)
    {
        // $article->title = $request->title;
        // $article->body = $request->body;

        $article->fill($request->all());
        $article->user_id = $request->user()->id;
        $article->save();

        // request->tagsはpassedValidationメソッドによりコレクションになっている
        // each() : 第一引数にはコレクションの値、第二引数にはコレクションのキー
        // use()を使うことで、クロージャー外の変数を使える。
        $request->tags->each(function ($tagName) use ($article) {
            // カラムに存在すればそのモデルを返し、存在しなければテーブルに保存してそのモデルを返す。
            $tag = Tag::firstOrCreate(['name'=>$tagName]);
            // 記事とタグの紐付け(article_tagテーブルへのレコードの保存)
            $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }


    /**
     * 記事の編集画面
     * @method GET
     */
    public function edit(Article $article)
    {
        // タグ名のみ取得
        $tagNames = $article->tags->map(function ($tag) {
            return ['text' => $tag->name];
        });

        // 全てのタグ名を取得
        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return view('articles.edit', [
            'article' => $article,
            'tagNames' => $tagNames,
            'allTagNames' => $allTagNames
        ]);
    }


    /**
     * 記事の更新処理
     * @method POST
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all())->save();

        $article->tags()->detach();

        // articlesモデルとtagモデルの紐付け タグが存在しなければ登録
        $request->tags->each(function ($tagName) use ($article) {
            $tag = Tag::firstOrCreate(['name'=>$tagName]);
            $article->tags()->attach(($tag));
        });

        return redirect()->route('articles.index');
    }


    /**
     * 削除処理
     * @method POST
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index');
    }


    /**
     * 詳細画面
     * @method  GET
     */
    public function show(Article $article)
    {
        return view('articles.show', ['article' => $article]);
    }


    /**
     * いいね更新
     * @method patch
     */
    public function like(Request $request, Article $article)
    {
        // 削除 likesテーブルへの削除
        $article->likes()->detach($request->user()->id);
        // 登録 likesテーブルへの保存
        $article->likes()->attach($request->user()->id);

        // クライアントへレスポンス
        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }


    /**
     * いいね解除
     * @method delete
     */
    public function unlike(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

}
