<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheduled_email_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheduled_email_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('sent_at')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['scheduled_email_id', 'status']);
            $table->unique(['scheduled_email_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_email_recipients');
    }
};
