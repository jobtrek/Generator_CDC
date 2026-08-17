<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('cdcs')->where('status', 'brouillon')->update(['status' => 'draft']);
        DB::table('cdcs')->where('status', 'terminé')->update(['status' => 'completed']);
    }

    public function down(): void
    {
        DB::table('cdcs')->where('status', 'draft')->update(['status' => 'brouillon']);
        DB::table('cdcs')->where('status', 'completed')->update(['status' => 'terminé']);
    }
};
