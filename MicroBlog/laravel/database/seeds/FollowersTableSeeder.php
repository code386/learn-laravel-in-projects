<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 获得所有用户信息
        $users = User::all();
        // id第一用户信息
        $user = $users->first();
        // 获取第一个用户的id
        $user_id = $user->id;
        // 去掉第一个id，剩下的所有人的信息
        $followers = $users->slice($user_id);
        // 剩下所有人的id数组
        $follower_ids = $followers->pluck('id')->toArray();

        // 第一个用户关注所有人
        $user->follow($follower_ids);
        // 所有人关注第一个人
        foreach ($followers as $follower) {
            $follower->follow($user_id);
        }
    }
}
