<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearTrackUploadLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('sear_track_upload_log', function (Blueprint $table){
              
                $table->increments('id');
                $table->string('uploaded_by')->nullable();
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
        Schema::drop('sear_track_upload_log');
    }
}
