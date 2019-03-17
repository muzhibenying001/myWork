<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	# 指定表名
	protected $table = 'user';
    # 设置字段黑名单
    protected $guarded = [];
}
