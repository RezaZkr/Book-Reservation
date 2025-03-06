<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Book\Database\Seeders\AttributeBookVersionSeeder;
use Modules\Book\Models\BookVersion;
use Modules\Attribute\Models\Attribute;
use Modules\Attribute\Models\Value;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attribute_book_version', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(BookVersion::class)->index()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Attribute::class)->index()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Value::class)->index()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('attribute_title');
            $table->string('value_title');
            $table->unique(['book_version_id', 'attribute_id']);
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => AttributeBookVersionSeeder::class,
            '--force' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_book_version');
    }
};
