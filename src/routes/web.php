<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;


// 認証パス
Auth::routes();

// 一覧表示
Route::get('/', [ArticleController::class, 'index'])
    ->name('articles.index');


// 記事リソース
Route::resource('/articles', ArticleController::class)
    ->except(['index', 'show'])     // index,showメソッド除外
    ->middleware('auth');           // ログインしていないと遷移しない


// 詳細画面
Route::resource('articles', ArticleController::class)
    ->only(['show']);       // showアクションのみを指定


// いいね機能
Route::prefix('articles')->name('articles.')->group(function () {
    Route::put('/{article}/like', [ArticleController::class, 'like'])
        ->name('like')
        ->middleware('auth');
    Route::delete('/{article}/like', [ArticleController::class, 'unlike'])
        ->name('unlike')
        ->middleware('auth');
});


// タグ別の記事一覧表示
Route::get('tags/{name}', [TagController::class, 'show'])
    ->name('tags.show');


// ユーザーページ
Route::prefix('users')->name('users.')->group(function () {
    // ユーザーの記事一覧
    Route::get('/{name}', [UserController::class, 'show'])
        ->name('show');
    // ユーザーがいいねした記事一覧
    Route::get('/{name}/likes', [UserController::class, 'likes'])
        ->name('likes');
    // フォロー一覧
    Route::get('/{name}/followings', [UserController::class, 'followings'])
        ->name('followings');
    // フォロワー一覧
    Route::get('/{name}/followers', [UserController::class, 'followers'])
        ->name('followers');

    // ログイン時
    Route::middleware('auth')->group(function () {
        // フォロー機能(nameに入るのはフォローする名前)
        Route::put('/{name}/follow', [UserController::class, 'follow'])
            ->name('follow');
        // フォロー解除機能(nameに入るのはフォロー解除される名前)
        Route::delete('/{name}/follow', [UserController::class, 'follow'])
            ->name('unfollow');
    });
});



