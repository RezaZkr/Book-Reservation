<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Book\Models\BookVersion;
use Modules\Branch\Models\Branch;
use Modules\Reservation\Enums\ReservationStatusEnum;
use Modules\User\Models\User;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->index()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(Branch::class)->index()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(BookVersion::class)->index()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->unsignedTinyInteger('user_penalty_point')->default(0);
            $table->string('status', 25)->default(ReservationStatusEnum::PENDING);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
