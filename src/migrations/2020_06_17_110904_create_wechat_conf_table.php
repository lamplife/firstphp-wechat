<?php

/**
 * Author: 狂奔的螞蟻 <www.firstphp.com>
 * Date: 2020/6/17
 * Time: 11:27 AM
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatConfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_conf', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->comment('应用名称');
            $table->string('app_id', 255);
            $table->string('app_secret', 255);
            $table->string('access_token', 255)->default('');
            $table->dateTime('expires_in')->comment('TOKEN过期');
            $table->timestamps();
            $table->engine = 'MyISAM';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wechat_conf');
    }
}
