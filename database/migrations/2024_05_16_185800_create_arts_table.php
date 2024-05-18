<?php

use App\Models\Artist;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('arts', function (Blueprint $table) {
            $table->id();
            $table->string("raw_id");
            $table->string("name");
            $table->string("title");
            $table->json("submission");
            $table->longText("description")->nullable();
            $table->longText("image_path")->nullable();
            $table->string("countryOfResidence");
            $table->foreignIdFor(Artist::class)->nullable();
            $table->string('openCallId')->nullable();
            $table->longText("submissionId")->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arts');
    }
};
