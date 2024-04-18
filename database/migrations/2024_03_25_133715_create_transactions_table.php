<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->decimal('amount', 10, 2); // Adjust the precision and scale as needed
            $table->string('status')->default(0);
            $table->string('transaction_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Define foreign key constraint
            // Add other relevant fields here
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
