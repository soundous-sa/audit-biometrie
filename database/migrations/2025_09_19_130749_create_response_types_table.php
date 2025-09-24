<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('response_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->timestamps();
        });

        // ajout la fk f audit
        Schema::table('audits', function (Blueprint $table) {
            $table->foreignId('response_type_id')
                  ->nullable()
                  ->constrained('response_types')
                  ->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('response_types');
    }
};