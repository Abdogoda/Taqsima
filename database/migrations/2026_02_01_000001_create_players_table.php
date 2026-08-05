<?php

use App\Enums\PlayerPosition;
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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->unsignedTinyInteger('overall')->default(70);
            $table->enum('position', PlayerPosition::values())->nullable();
            $table->boolean('is_injured')->default(false);
            $table->boolean('is_available')->default(true);
            $table->unsignedInteger('matches_played')->default(0);
            $table->unsignedInteger('wins')->default(0);
            $table->unsignedInteger('losses')->default(0);
            $table->unsignedInteger('draws')->default(0);
            $table->unsignedInteger('goals')->default(0);
            $table->unsignedInteger('assists')->default(0);
            $table->unsignedInteger('own_goals')->default(0);
            $table->unsignedInteger('clean_sheets')->default(0);
            $table->unsignedInteger('attendance')->default(0);
            $table->unsignedInteger('minutes_played')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_available']);
            $table->index(['user_id', 'overall']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
