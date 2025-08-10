<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::create('attendance_records', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained()->restrictOnDelete();
        $table->date('work_date');
        $table->dateTime('clock_in');
        $table->dateTime('clock_out')->nullable();
        $table->string('notes', 255)->nullable();

        $table->unique(['user_id', 'work_date']);
        $table->index(['user_id', 'clock_in']);
        $table->index(['user_id', 'clock_out']);

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};