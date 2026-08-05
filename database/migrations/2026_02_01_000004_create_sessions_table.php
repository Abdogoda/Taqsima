<?php

use App\Enums\RotationMode;
use App\Enums\SessionStatus;
use App\Enums\TeamGenerationMode;
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
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->date('play_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->unsignedInteger('duration_minutes')->default(60);
            $table->string('field_name')->nullable();
            $table->string('field_location')->nullable();
            $table->unsignedInteger('players_per_team')->default(5);
            $table->unsignedInteger('number_of_teams')->default(2);
            $table->enum('team_generation_mode', TeamGenerationMode::values())->default(TeamGenerationMode::Automatic->value);
            $table->enum('rotation_mode', RotationMode::values())->default(RotationMode::WinnerStays->value);
            $table->enum('status', SessionStatus::values())->default(SessionStatus::Draft->value);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'play_date']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};
