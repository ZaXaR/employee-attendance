<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dateTime('clock_in')->nullable()->change();
            $table->dateTime('clock_out')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dateTime('clock_in')->nullable(false)->change();
            $table->dateTime('clock_out')->nullable(false)->change();
        });
    }
};
