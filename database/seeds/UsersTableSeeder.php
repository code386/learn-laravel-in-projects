<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 准备数据集合
        $users = factory(User::class)->times(50)->make();
        // 插入数据
        User::insert( $users->makeVisible(['password', 'remember_token'])->toArray() );

        // 将id为1的用户修改，作为自己的用户
        $user = User::find(1);
        $user->name = 'admin';
        $user->email = 'admin@example.com';
        $user->is_admin = true;
        $user->save();
    }
}
