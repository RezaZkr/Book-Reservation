<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Attribute\Models\Attribute;
use Modules\Attribute\Database\Seeders\AttributeSeeder;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('values', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Attribute::class)->index()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('title')->index();
            $table->unique(['attribute_id', 'title']);
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => AttributeSeeder::class,
            '--force' => app()->isLocal(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('values');
    }
};
