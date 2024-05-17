<?php

use App\Enums\OrderStatusEnum;
use App\Models\Rate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Rate::class)->constrained();
            $table->string("tracking_code")->nullable();
            $table->float("amount_paid");
            $table->float("amount_received")->default(0);
            $table->float("rate_state_value");
            $table->string("email_address");
            $table->string("status")->default(OrderStatusEnum::getDefaultStatus());
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
