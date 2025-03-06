<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Book\Models\BookVersion;
use Modules\Branch\Models\Branch;
use Modules\User\Models\User;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('book_state_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Branch::class)->index()->constrained();
            $table->foreignIdFor(BookVersion::class)->index()->constrained();
            $table->foreignIdFor(User::class)->index()->constrained();
            $table->string('status', 25);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_state_tracks');
    }
};
