<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;


class UserController extends Controller
{
    /**
     * ユーザーページ表示
     */
    public function show(string $name)
    {
        $user = User::where('name', $name)->first()
            ->load(['articles.user', 'articles.likes', 'articles.tags']);;

        // 降順にソートして記事モデルを取得
        $articles = $user->articles->sortByDesc('created_at');

        return view('users.show',[
            'user' => $user,
            'articles' => $articles
        ]);
    }


    /**
     * ユーザーページ いいね表示
     */
    public function likes(string $name)
    {
        $user = User::where('name', $name)->first()
            ->load(['likes.user', 'likes.likes', 'likes.tags']);;
        $articles = $user->likes->sortByDesc('created_at');
        return view('users.likes', [
            'user' => $user,
            'articles' => $articles
        ]);
    }


    /**
     * ユーザーページ フォロー一覧表示
     */
    public function followings(string $name)
    {
        $user = User::where('name', $name)->first()
            ->load('followings.followers');
        $followings = $user->followings->sortByDesc('create_at');
        return view('users.followings', [
            'user' => $user,
            'followings' => $followings,
        ]);
    }


    /**
     * ユーザーページ フォロワー一覧表示
     */
    public function followers(string $name)
    {
        $user = User::where('name', $name)->first()
            ->load('followers.followers');
        $followers = $user->followers->sortByDesc('created_at');
        return view('users.followers', [
            'user' => $user,
            'followers' => $followers,
        ]);
    }


    /**
     * フォロー処理
     *
     * @method PUT
     * @param Illuminate\Http\Request $request 画面から送られてきた値
     * @param string $name フォローする名前 URL:users/{name}/follow
     * @return array クライアントにレスポンス
     */
    public function follow(Request $request, string $name)
    {
        $user = User::where('name', $name)->first();
        if ($user->id === $request->user()->id){
            return abort('404', 'Cannot follow yourself');
        }

        // $request->user()で、リクエストを行なったユーザーのユーザーモデルが返る
        $request->user()->followings()->detach($user);
        $request->user()->followings()->attach($user);

        return ['name' => $name];
    }


    /**
     * フォロー解除処理
     *
     * @method DELETE
     * @param Illuminate\Http\Request $request 画面から送られてきた値
     * @param string $name フォローする名前 URL:users/{name}/follow
     * @return array クライアントにレスポンス
     */
    public function unfollow(Request $request, string $name)
    {
        $user = User::where('name', $name)->first();
        if ($user->id === $request->user()->id){
            return abort('404', 'Cannot follow yourself.');
        }

        // $request->user()で、リクエストを行なったユーザーのユーザーモデルが返る
        $request->user()->followings()->detach($user);

        return ['name' => $name];
    }
}
