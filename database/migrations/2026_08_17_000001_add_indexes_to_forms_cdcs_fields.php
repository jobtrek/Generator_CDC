<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('cdcs', function (Blueprint $table) {
            $table->index('form_id');
            $table->index('user_id');
        });

        Schema::table('fields', function (Blueprint $table) {
            $table->index('order_index');
        });
    }

    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('cdcs', function (Blueprint $table) {
            $table->dropIndex(['form_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('fields', function (Blueprint $table) {
            $table->dropIndex(['order_index']);
        });
    }
};
