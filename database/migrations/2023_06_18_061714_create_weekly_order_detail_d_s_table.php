<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeeklyOrderDetailDSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_order_detail_d_s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('shelf_code')->nullable();
            $table->string('product_name');
            $table->string('checked')->nullable();
            $table->decimal('quantity');
            $table->decimal('picked')->nullable();
            $table->unsignedBigInteger('weekly_order_id');
            $table->foreign('weekly_order_id')->references('id')->on('weekly_orders')->onDelete('cascade');
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
        Schema::dropIfExists('weekly_order_detail_d_s');
    }
}
