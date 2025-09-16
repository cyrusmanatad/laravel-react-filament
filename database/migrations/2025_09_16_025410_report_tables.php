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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->string('psr_code')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });
        

        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('div_code')->unique();
            $table->string('div_desc');
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('cust_code')->unique();
            $table->string('name');
            $table->text('ship_to_address');
            $table->text('bill_to_address')->nullable();
            $table->string('ship_to_site_name');
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });

        Schema::create('customer_personnels', function (Blueprint $table) {
            $table->id();
            
            $table->string('cust_code');
            // $table->foreign('cust_code')->references('cust_code')->on('customers')->cascadeOnDelete();

            $table->string('div_code');
            // $table->foreign('div_code')->references('div_code')->on('divisions')->cascadeOnDelete();

            $table->string('psr_code');
            // $table->foreign('psr_code')->references('psr_code')->on('personnels')->cascadeOnDelete();

            $table->string('emp_id')->nullable();
            $table->string('bp_code');
            $table->string('bm_code');
            $table->timestamps();
            $table->softDeletes('deleted_at');
            $table->unique(['cust_code', 'div_code', 'psr_code'], 'unq_cust_psr');
        });

        Schema::create('brand_managers', function (Blueprint $table) {
            $table->id();
            $table->string('site_user_id');
            $table->string('cust_code');
            $table->string('bm_email')->nullable();
            $table->string('bm_code');
            $table->string('bm_name')->nullable();
            $table->string('bp_code');
            $table->string('bp_name')->nullable();
            $table->unique(['site_user_id', 'cust_code', 'bp_code'], 'unq_bmbp');
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });

        Schema::create('branch_plants', function (Blueprint $table) {
            $table->id();
            $table->string('branch_code')->unique();
            $table->string('branch_desc');
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });

        Schema::create('skus', function (Blueprint $table) {
            $table->id();
            $table->string('sku_code')->unique();
            $table->string('sku_desc');
            $table->string('tagging');
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });

        Schema::create('uts_skus', function (Blueprint $table) {
            $table->id();
            $table->string('branch_code')->nullable();
            $table->string('sku_code');
            $table->string('div_code');
            $table->string('cust_site')->nullable();
            $table->string('sku_desc');
            $table->string('sku_uom');
            $table->string('sku_price')->nullable();
            $table->string('matrix_price')->nullable();
            $table->unique(['branch_code', 'sku_code', 'div_code', 'cust_site'], 'unq_uts_sku');
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnels');
        Schema::dropIfExists('divisions');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('customer_personnels');
        Schema::dropIfExists('brand_managers');
        Schema::dropIfExists('branch_plants');
        Schema::dropIfExists('skus');
        Schema::dropIfExists('uts_skus');
    }
};
