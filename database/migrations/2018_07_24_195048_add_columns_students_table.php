<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->date('year_month')
                ->after('username')
                ->nullable()
                ->default(null)
                ->comment = "Student year and month";

            $table->text('observations')
                ->after('discount')
                ->nullable()
                ->default(null)
                ->comment = "Student year and month";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function(Blueprint $table) {
            $table->dropColumn('year_month');
            $table->dropColumn('observations');
        });
    }
}
