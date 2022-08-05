<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('key_id');
            $table->unsignedBigInteger('technician_id');
            $table->string('status');
            $table->text('note')->nullable();
            $table->timestamps();
            // Enable Soft Deletes (optional)
            $table->softDeletes();
            // Foreign Keys Constrains
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->foreign('key_id')->references('id')->on('keys');
            $table->foreign('technician_id')->references('id')->on('technicians');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
