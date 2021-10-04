<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnExecuteTimeInTelegramLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram_logs', function (Blueprint $table) {
            $table
                ->integer("execute_time")
                ->after("command")
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telegram_logs', function (Blueprint $table) {
            $table->dropColumn("execute_time");
        });
    }
}
