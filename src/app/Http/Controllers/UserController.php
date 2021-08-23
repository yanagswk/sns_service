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
        $user = User::where('name', $name)->first();
        return view('users.show',[
            'user' => $user
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
