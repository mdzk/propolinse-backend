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
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('newcart_id');
            $table->string('nama');
            $table->string('alamat');
            $table->Integer('kode_pos');
            $table->string('pengiriman');
            $table->Integer('ongkir');
            $table->Integer('bank');
            $table->Integer('image');
            $table->Integer('total_bayar');
            $table->foreign('newcart_id')->references('id')->on('new_carts')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkouts');
    }
};
