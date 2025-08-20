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
            $table->id();
            $table->string('nik', 16);
            $table->string('name', 100);
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->date('birth_date');
            $table->string('birth_place', 100);
            $table->text('address');
            $table->string('religion', 50);
            $table->enum('marital_status', ['Belum Menikah', 'Menikah', 'Cerai', 'Duda/Janda']);
            $table->string('occupation', 100);
            $table->string('phone', 15);
            $table->enum('status', ['Hidup', 'Pindah', 'Meninggal dunia'])->default('Hidup');
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
