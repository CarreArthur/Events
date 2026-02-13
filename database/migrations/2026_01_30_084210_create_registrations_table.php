<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('registrations', function (Blueprint $table) {
        $table->id();

        $table->foreignId('event_id')->constrained()->cascadeOnDelete();

        $table->foreignId('parent_registration_id')
            ->nullable()
            ->constrained('registrations')
            ->nullOnDelete();

        $table->string('invite_token')->unique();

        $table->string('guest_name')->nullable();
        $table->string('guest_email'); // obligatoire

        $table->enum('status', ['INVITED', 'REGISTERED', 'CANCELLED'])->default('INVITED');
        $table->boolean('is_attending')->default(true);

        $table->text('dietary_info')->nullable();

        $table->timestamp('email_sent_at')->nullable();
        $table->timestamp('registered_at')->nullable();

        $table->timestamps();

        $table->index(['event_id', 'status']);
        $table->index(['parent_registration_id']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
