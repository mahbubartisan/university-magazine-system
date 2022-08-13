<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContributionContributionSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('contribution_contribution_setting', function (Blueprint $table) {
            $table->integer('cont_id')->unsigned()->index();;
            $table->foreign('cont_id')->references('id')->on('contributions')->onDelete('cascade');
            $table->integer('cont_setting_id')->unsigned()->index();;
            $table->foreign('cont_setting_id')->references('id')->on('contribution_settings')->onDelete('cascade');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('contribution_contribution_setting');
    }
}
