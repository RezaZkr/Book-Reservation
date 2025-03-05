<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\User\Database\Seeders\UserSeeder;
use Modules\User\Enums\UserTypeEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('type', 20)->default(UserTypeEnum::NORMAL);
            $table->unsignedMediumInteger('penalty_points')->default(0);
            $table->boolean('restricted')->default(false);
            $table->string('password');
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => UserSeeder::class,
            '--force' => app()->isLocal(),
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
