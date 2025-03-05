<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Book\Enums\BookVersionConditionEnum;
use Modules\Book\Enums\BookVersionStatusEnum;
use Modules\Book\Models\Book;
use Modules\Branch\Models\Branch;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('book_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Branch::class)->index()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(Book::class)->index()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('condition', 25)->default(BookVersionConditionEnum::NEW);
            $table->string('status', 25)->default(BookVersionStatusEnum::AVAILABLE);
            $table->boolean('vip')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_versions');
    }
};
