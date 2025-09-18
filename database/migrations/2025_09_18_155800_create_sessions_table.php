<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('program_id');
            $table->string('title');             // Nama sesi
            $table->string('venue');             // Tempat sesi
            $table->dateTime('start_time');      // Tarikh & masa mula
            $table->dateTime('end_time');        // Tarikh & masa tamat
            $table->boolean('publish_status');   // 1 = aktif, 0 = tidak aktif
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('program_id')
                  ->references('id')->on('programs')
                  ->onDelete('cascade'); // Kalau program padam, sesi pun padam
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}
