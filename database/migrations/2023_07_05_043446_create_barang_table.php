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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->integer('kd_brg');
            $table->integer('hrg_brg');
            $table->integer('stok');
            $table->string('jenis_brg', 100);
            $table->string('nm_brg', 100);
            $table->string('tag', 100);
            $table->string('type_size', 50);
            $table->string('ket_brg', 100);
            $table->text('desk_umum');
            $table->integer('berat_brg');
            $table->string('image');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
