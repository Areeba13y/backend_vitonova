<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fix D-01: users.email and users.password were nullable, which allowed
 * broken account states where users could not log in or receive emails.
 *
 * Before altering constraints:
 *  - Any existing rows with NULL email are given a generated placeholder
 *    so the NOT NULL constraint can be applied safely.
 *  - Any existing rows with NULL password are given a random bcrypt hash.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Step 1: Patch existing NULL values so the constraint change
        //           does not fail on rows that were inserted before this fix.
        DB::table('users')
            ->whereNull('email')
            ->cursor()
            ->each(function ($user) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['email' => 'placeholder_' . $user->id . '@unknown.invalid']);
            });

        DB::table('users')
            ->whereNull('password')
            ->update(['password' => bcrypt(str_pad(random_bytes(12), 16, '0'))]);

        // ── Step 2: Apply NOT NULL constraint on both columns.
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        // Revert both columns back to nullable.
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
        });
    }
};
