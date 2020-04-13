<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'statuses';

    // 一对多 用户关联多个文章
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
