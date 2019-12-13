<?php

use App\Models\DrawAttempt;
use App\Models\Winner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrawAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draw_attempts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->boolean('is_generated_randomly')->default(false);
            $table->integer('winning_number');
            $table->enum('prize', DrawAttempt::PRIZES);

            $table->unsignedBigInteger('winning_number_id')->nullable();
            $table->foreign('winning_number_id')->references('id')->on('winning_numbers');             
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('draw_attempts');
    }
}
