<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    // 返回真假，判断现在登录的用户A，是否已经关注过用户B
    // 通过判断B用户的id是否在用户A的关注列表里
    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }

    // 关注
    public function follow($user_ids)
    {
        if ( !is_array($user_ids) ) {
            $user_ids = compact("user_ids");
        }
        $this->followings()->sync($user_ids, false);
    }

    // 取消关注
    public function unfollow($user_ids)
    {
        if ( !is_array($user_ids) ) {
            $user_ids = compact("user_ids");
        }
        $this->followings()->detach($user_ids);
    }

    // 多对多获得粉丝列表
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    // 多对多获得关注的人列表
    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    // 查询用户发布的微博和关注的人发布的微博
    public function feed()
    {
        // 获得用户关注的人的所有id
        $user_ids = $this->followings->pluck('id')->toArray();
        // 将自己的id也放入$user_ids中
        array_push($user_ids, $this->id);
        //return $this->statuses()->orderBy('created_at', 'desc');
        return Status::whereIn('user_id', $user_ids)->with('user')->orderBy('created_at', 'desc');
    }

    // 一个用户拥有多个发布的文章
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 在模型初始化的时候，生成用户激活令牌
    public static function boot()
    {
        parent::boot();
        static::creating(function( $user ) {
            $user->activation_token = Str::random(30);
        });
    }

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }
}
