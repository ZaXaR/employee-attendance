<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // boolean в MySQL = TINYINT(1)
            $table->boolean('is_suspended')
                ->default(false)
                ->after('is_admin');
            $table->index(['is_suspended', 'is_admin'], 'users_is_suspended_is_admin_index');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_is_suspended_is_admin_index');
            $table->dropColumn('is_suspended');
        });
    }
};