<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublishStatusToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('publish_status');
            $table->softDeletes(); 
        });
    }
    
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('publish_status');
            $table->dropSoftDeletes();
        });
    }
}
