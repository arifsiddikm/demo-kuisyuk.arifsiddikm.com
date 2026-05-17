<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Quizzes table
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('slug', 10)->unique(); // short unique code
            $table->boolean('is_active')->default(true);
            // Custom primary key field config
            $table->string('primary_key_label')->default('Nama'); // e.g., NIS, NISN, KTP
            $table->boolean('primary_key_enabled')->default(false);
            $table->boolean('primary_key_unique')->default(false); // can only submit once
            $table->integer('time_limit')->nullable(); // in minutes, null = no limit
            $table->timestamps();
        });

        // Questions table
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->string('question_image')->nullable();
            $table->enum('question_type', ['multiple_choice', 'essay'])->default('multiple_choice');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Options table (for multiple choice)
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->text('option_text');
            $table->string('option_image')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Quiz responses (one per submission)
        Schema::create('quiz_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->string('respondent_name');
            $table->string('primary_key_value')->nullable(); // NIS/NISN/KTP value
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        // Answers table
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_response_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->foreignId('option_id')->nullable()->constrained()->onDelete('set null');
            $table->text('essay_answer')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
        Schema::dropIfExists('quiz_responses');
        Schema::dropIfExists('options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('quizzes');
    }
};
