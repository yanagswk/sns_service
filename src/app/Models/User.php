<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Mail\BareMail;
use App\Notifications\PasswordResetNotification;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * メール設定
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token, new BareMail()));
    }


    /**
     * Userモデルとのリレーション
     * 中間テーブル : follows
     *
     * ユーザーモデルから「フォロワーであるユーザー」のモデルにアクセス可能にする必要がある
     * リレーション元のusersテーブルのidは、中間テーブルのfollowee_idと紐付く
     * リレーション先のusersテーブルのidは、中間テーブルのfollower_idと紐付く
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'followee_id', 'follower_id')
            ->withTimestamps();
    }


    /**
     * Userモデルとのリレーション
     * 中間テーブル : follows
     *
     * これからフォローするユーザー、あるいはフォロー中のユーザーのモデルに
     * アクセス可能にするためのリレーションメソッド
     */
    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followee_id')
            ->withTimestamps();
    }


    /**
     * ユーザーをフォロー中かどうかを判定
     */
    public function isFollowedBy(?User $user): bool
    {
        // dd((bool) $this->followers->where('id', $user->id)->count());
        return $user
            ? (bool) $this->followers->where('id', $user->id)->count()
            : false;
    }


    /**
     * ユーザーモデルのフォロワー(のユーザーモデル)が、コレクションで返る。
     * アクセサ使用 : $user->count_followers
     */
    public function getCountFollowersAttribute(): int
    {
        return $this->followers->count();
    }


    /**
     * ユーザーモデルが現在フォロー中のユーザー数が返る。
     * アクセサ使用 : $user->count_followings
     */
    public function getCountFollowingsAttribute(): int
    {
        return $this->followings->count();
    }
}
