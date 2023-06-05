<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pick_lists', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('custom_pick_list_id')->nullable();
            $table->date('sailing_date')->nullable();
            $table->date('picked_date')->nullable();
            $table->date('date')->nullable();
            $table->date('date_requested')->nullable();
            $table->string('picked_by')->nullable();
            $table->string('checked_by')->nullable();
            $table->string('missing')->nullable();
            $table->text('ship_name')->nullable();
            $table->text('ship_address')->nullable();
            $table->string('image')->nullable();
            $table->string('pick_list_reference_id')->nullable();
            $table->decimal('total_quantity_to_pick')->nullable();
            $table->decimal('total_items_on_pick_list')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

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
        Schema::dropIfExists('pick_lists');
    }
}
