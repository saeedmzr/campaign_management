<?php

use App\Models\Art;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Art::class)->constrained("arts")->cascadeOnDelete();
            $table->boolean("is_liked")->default(false);
            $table->boolean("is_voted")->default(false);
            $table->timestamps();
            $table->index(['is_liked',"is_voted","art_id","user_id"]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
