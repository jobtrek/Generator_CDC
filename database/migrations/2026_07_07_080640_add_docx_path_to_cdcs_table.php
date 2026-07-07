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
        Schema::table('cdcs', function (Blueprint $table) {
            $table->string('docx_path')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('cdcs', function (Blueprint $table) {
            $table->dropColumn('docx_path');
        });
    }
};
