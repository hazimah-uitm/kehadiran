<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('category')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
}
