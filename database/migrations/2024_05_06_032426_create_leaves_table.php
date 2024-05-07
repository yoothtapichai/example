<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->integer('leave_type_id');
            $table->integer('user_id');
            $table->enum('leave_period', ['1', '2', '3']);//['MORNING', 'AFTERNOON', 'FULL_DAY']
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('date');
            $table->string('phone_number');
            $table->text('leave_reason');
            $table->enum('leave_status', ['1', '2', '3']);//['PENDING', 'APPROVED', 'REJECTED']
            $table->text('admin_comment')->nullable();
            $table->integer('admin_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leaves');
    }
}
