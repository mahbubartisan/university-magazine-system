<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('contribution_setting_id')->unsigned();
            $table->foreign('contribution_setting_id')->references('id')->on('contribution_settings')->onDelete('cascade');
            $table->integer('faculty_id')->unsigned();
            $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('cascade');
            $table->string('title');
            $table->string('file');
            $table->enum('type',['document','image']);
            $table->enum('status',['Pending','Approved','Disapproved']);
            $table->enum('m_coordinator_notify',['unread','read']);
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
        Schema::dropIfExists('contributions');
    }
}
