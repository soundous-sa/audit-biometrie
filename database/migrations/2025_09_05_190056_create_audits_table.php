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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('etab_id'); // établissement ID
            $table->unsignedBigInteger('fonct_id'); // fonctionnaire ID
            $table->date('date_audit');
            $table->integer('nb_detenus')->default(0);
            $table->integer('nb_edited_fingerprints')->default(0);
            $table->integer('nb_verified_fingerprints')->default(0);
            $table->integer('nb_without_fingerprints')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
