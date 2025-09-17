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
        Schema::create('potongan_gajis', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_potongan'); // Potongan seperti keterlambatan, cuti, dsb.
            $table->decimal('nilai_potongan', 8, 2); // Nilai potongan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potongan_gajis');
    }
};
