<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cdcs', function (Blueprint $table) {
            $table->dropForeign(['form_id']);
            $table->foreignId('form_id')
                ->nullable()
                ->change()
                ->constrained()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cdcs', function (Blueprint $table) {
            $table->dropForeign(['form_id']);

            $table->foreignId('form_id')
                ->nullable(false)
                ->change()
                ->constrained()
                ->onDelete('cascade');
        });
    }
};
