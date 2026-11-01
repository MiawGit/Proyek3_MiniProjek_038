<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('area_id')->constrained()->restrictOnDelete();
            $table->text('description');
            $table->dateTime('incident_at');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('status', 30)->default('baru');
            $table->string('evidence_path')->nullable();
            $table->string('forwarded_to')->nullable();
            $table->dateTime('forwarded_at')->nullable();
            $table->foreignId('forwarded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
