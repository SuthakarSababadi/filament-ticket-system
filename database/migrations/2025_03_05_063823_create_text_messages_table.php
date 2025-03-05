<?php

use App\Models\TextMessage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('text_messages', function (Blueprint $table) {
            $table->id();
            $table->string('message');
            $table->text('response')->nullable();
            $table->foreignId('sent_to')->constrained('users');
            $table->foreignId('sent_by')->constrained('users');
            $table->enum('status',TextMessage::STATUS)->default(TextMessage::STATUS['Pending']);
            $table->text('remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('text_messages');
    }
};
