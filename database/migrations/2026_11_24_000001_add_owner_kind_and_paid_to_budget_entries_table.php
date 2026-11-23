<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('budget_entries', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')
                  ->constrained()->nullOnDelete();
            $table->string('kind', 10)->nullable()->after('type');   // need | want (expenses only)
            $table->boolean('is_paid')->default(true)->after('entry_date');
        });
    }

    public function down(): void
    {
        Schema::table('budget_entries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['kind', 'is_paid']);
        });
    }
};