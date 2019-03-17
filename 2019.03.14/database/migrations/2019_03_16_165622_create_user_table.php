<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        # 创建用户表
        Schema::create('user', function (Blueprint $table) {
            # 自增ID
            $table->increments('id');
            # 用户名
            $table->string('username',50)->default('')->comment('用户名');
            # 密码
            $table->char('password',32)->default('')->comment('密码');
            # 软删除字段
            $table->softDeletes();
            # 维护表的时间字段
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
