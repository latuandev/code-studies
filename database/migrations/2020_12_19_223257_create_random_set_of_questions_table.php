<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRandomSetOfQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('random_set_of_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();
            $table->string('subject_set_code')->nullable();
            $table->text('question')->nullable();
            $table->text('ans_a')->nullable();
            $table->text('ans_b')->nullable();
            $table->text('ans_c')->nullable();
            $table->text('ans_d')->nullable();
            $table->string('ans_correct')->nullable();
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
        Schema::dropIfExists('random_set_of_questions');
    }
}
