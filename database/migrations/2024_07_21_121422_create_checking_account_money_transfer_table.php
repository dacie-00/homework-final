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
        Schema::create('checking_account_money_transfer', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('checking_account_id')->constrained();
            $table->foreignUuid('money_transfer_id')->constrained();
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checking_account_money_transfer');
    }
};
