<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
# 引入软删除类
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    # 设置字段黑名单
    protected $guarded = [];
    # 设置软删除
    use SoftDeletes;
    protected $dates = ['delete_at'];
}
