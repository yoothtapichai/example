<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_login', function (Blueprint $table) {
            $table->id();
            $table->string('email'); // 
            $table->enum('login_status', ['success', 'failed'])->default('failed'); // 
            $table->timestamp('login_time'); // 
            $table->text('user_location')->nullable();
            $table->string('ip_address'); // 
            $table->text('msg')->nullable();
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_login');
    }
}
