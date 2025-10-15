<?php
// database/migrations/2025_01_XX_add_section_id_to_fields_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->string('section')->nullable()->after('form_id');
            $table->string('subsection')->nullable()->after('section');
        });
    }

    public function down(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->dropColumn(['section', 'subsection']);
        });
    }
};
