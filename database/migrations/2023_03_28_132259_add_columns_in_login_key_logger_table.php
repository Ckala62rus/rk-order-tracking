<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInLoginKeyLoggerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('login_key_loggers', function (Blueprint $table) {
            $table->string('department')->nullable()->after('fio');
            $table->string('organization')->nullable()->after('department');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('login_key_loggers', function (Blueprint $table) {
            $table->dropColumn('organization');
            $table->dropColumn('department');
        });
    }
}
