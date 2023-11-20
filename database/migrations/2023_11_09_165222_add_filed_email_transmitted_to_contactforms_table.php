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
        Schema::table('contactforms', function (Blueprint $table) {
            $table->timestamp('email_transmitted_at')->nullable()->after('source_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contactforms', function (Blueprint $table) {
            $table->dropColumn('email_transmitted_at');
        });
    }
};
