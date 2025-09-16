<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_types', function (Blueprint $table) {
            $table->id();
            $table->string('ot_code')->unique();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("psr_uid");
            $table->string('order_type');
            $table->foreign('order_type')->references('ot_code')->on('order_types')->cascadeOnDelete();
            $table->string('psr_code');
            $table->string('order_slip_number')->unique();
            $table->string('cust_code');
            $table->string('div_code');
            $table->string('branch_code');
            $table->string('delivery_mode');
            $table->string('remarks')->nullable();
            $table->date('delivery_date');
            $table->string('status');
            $table->string('invoice')->nullable()->index();
            $table->tinyInteger('attempt');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->string('order_slip_number');
            $table->foreign('order_slip_number')->references('order_slip_number')->on('orders')->cascadeOnDelete();
            $table->string('sku_code');
            $table->float('price');
            $table->string('uom', 20);
            $table->string('sku_type')->nullable();
            $table->string('ref_item')->nullable();
            $table->smallInteger('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_types');
    }
};
