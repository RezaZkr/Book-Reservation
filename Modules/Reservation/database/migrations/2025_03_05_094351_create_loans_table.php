<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Book\Models\BookVersion;
use Modules\Branch\Models\Branch;
use Modules\Reservation\Enums\LoanStatusEnum;
use Modules\User\Models\User;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->index()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(Branch::class)->index()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(BookVersion::class)->index()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('status', 25)->default(LoanStatusEnum::ACTIVE);
            $table->string('give_status', 25);
            $table->string('receive_status', 25)->nullable();
            $table->timestamp('loan_date');
            $table->timestamp('expiration_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
