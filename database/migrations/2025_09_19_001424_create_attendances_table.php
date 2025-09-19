<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('program_id');           
            $table->unsignedBigInteger('session_id')->nullable(); 
            $table->unsignedBigInteger('participant_id');          
            $table->string('participant_code')->nullable();        
            $table->softDeletes();
            $table->timestamps(); 

            // FK
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('cascade');

            // Elak duplicate: seorang peserta hanya sekali per entiti (sesi/program) 
            $table->unique(['program_id', 'session_id', 'participant_id'], 'attendance_unique');

            // Index bantu carian
            $table->index(['program_id', 'session_id']);
            $table->index(['participant_id']);
            $table->index(['participant_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
