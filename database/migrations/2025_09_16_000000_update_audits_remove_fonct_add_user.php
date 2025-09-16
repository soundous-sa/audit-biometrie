<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            if (!Schema::hasColumn('audits', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained('users')->cascadeOnDelete();
            }
            if (Schema::hasColumn('audits', 'fonct_id')) {
                $table->dropColumn('fonct_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            if (!Schema::hasColumn('audits', 'fonct_id')) {
                $table->unsignedBigInteger('fonct_id')->nullable();
            }
            if (Schema::hasColumn('audits', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
};
