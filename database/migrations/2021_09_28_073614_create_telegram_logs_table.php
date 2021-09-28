<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('telegram_user_id');
            $table->string('command');
            $table->timestamps();

//            $table
//                ->foreign('id')
//                ->references('id')
//                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_logs');
    }
}
