<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('program_id');   // FK -> programs.id
            $table->string('name');                     // nama peserta
            $table->string('ic_passport');              // IC / Passport (unik dalam program)
            $table->string('student_staff_id')->nullable();
            $table->string('nationality')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('institution')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('program_id')
                ->references('id')->on('programs')
                ->onDelete('cascade');

            // Unik per program (elak dua rekod IC/Passport sama dalam program sama)
            $table->unique(['program_id', 'ic_passport']);

            // Index carian
            $table->index(['program_id', 'name']);
            $table->index(['program_id', 'institution']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participants');
    }
}
