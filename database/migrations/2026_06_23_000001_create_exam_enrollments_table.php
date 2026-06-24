<?php

use App\Models\ExamTemplate;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ExamTemplate::class)->constrained()->cascadeOnDelete();
            $table->string('status')->default('active')->index();
            $table->string('payment_status')->default('manual_enrolled')->index();
            $table->timestamp('enrolled_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'exam_template_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_enrollments');
    }
};
