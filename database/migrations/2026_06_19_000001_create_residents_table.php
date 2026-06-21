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
        Schema::create('residents', function (Blueprint $table) {
            $table->string('nik')->primary();
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama');
            $table->string('pekerjaan');
            $table->text('alamat');
            $table->string('rt');
            $table->string('rw');
            $table->string('pendidikan');
            $table->string('status_perkawinan');
            $table->string('bantuan_sosial')->default('Tidak Ada'); // 'PKH', 'BPNT', 'BST', 'Tidak Ada'
            $table->boolean('is_umkm')->default(false);
            $table->string('nama_umkm')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
