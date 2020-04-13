<?php

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 只设置3个用户的微博
        $user_ids = ['1', '2', '3'];
        // 获得facker容器,用于随机user_ids
        $faker = app(Faker\Generator::class);
        // 创建随机数据
        $statuses = factory(Status::class)->times(100)->make()->each(function ($status) use ($user_ids, $faker){
            $status->user_id = $faker->randomElement($user_ids);
        });
        // 插入数据
        Status::insert($statuses->toArray());
    }
}
