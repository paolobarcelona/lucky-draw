<?php

use App\Models\Winner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWinnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('winners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('draw_attempt_id');
            $table->foreign('draw_attempt_id')->references('id')->on('draw_attempts');

            $table->unsignedBigInteger('winning_number_id')->nullable();
            $table->foreign('winning_number_id')->references('id')->on('winning_numbers');

            $table->unique(
                ['user_id', 'draw_attempt_id'],
                'unique_user_id_draw_attempt_id'
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('winners');
    }
}
