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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'id_number')) {
                $table->string('id_number')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('id_number');
            }
            if (!Schema::hasColumn('users', 'address')) {
                // If the typo column 'adress' exists, we could rename it, 
                // but for safety we just add the correct column.
                $table->string('address')->nullable()->after('phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'id_number')) {
                $table->dropColumn('id_number');
            }
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }
        });
    }
};
