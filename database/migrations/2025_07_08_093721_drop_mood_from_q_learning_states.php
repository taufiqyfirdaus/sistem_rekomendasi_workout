<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('q_learning_states', function (Blueprint $table) {
            $table->dropColumn('mood');
        });
    }

    public function down()
    {
        Schema::table('q_learning_states', function (Blueprint $table) {
            $table->enum('mood', ['Bagus', 'Buruk'])->after('kelengkapan_alat');
        });
    }

};