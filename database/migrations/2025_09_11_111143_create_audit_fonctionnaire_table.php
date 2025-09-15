<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        
        Schema::create('audit_fonctionnaire', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained();
            $table->foreignId('fonctionnaire_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_fonctionnaire');
    }
};
