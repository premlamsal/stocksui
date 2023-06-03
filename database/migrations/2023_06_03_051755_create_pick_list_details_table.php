<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickListDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pick_list_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pick_list_id');
            $table->foreign('pick_list_id')->references('id')->on('pick_lists')->onDelete('cascade');
            $table->string('shelf')->nullable();
            $table->string('requested')->nullable();
            $table->string('picked')->nullable();
            $table->string('description')->nullable();
            $table->string('quantity_picked')->nullable();

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
        Schema::dropIfExists('pick_list_details');
    }
}
