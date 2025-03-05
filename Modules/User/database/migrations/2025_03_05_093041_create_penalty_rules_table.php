<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\User\Database\Seeders\PenaltyRuleSeeder;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penalty_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('delay_days')->default(0);
            $table->unsignedMediumInteger('penalty_rate')->default(0);
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => PenaltyRuleSeeder::class,
            '--force' => app()->isLocal(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penalty_rules');
    }
};
