<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('tr_skm_result_answer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_skm_result_header');
            $table->unsignedBigInteger('id_skm_question');
            $table->text('desc_skm_question');
            $table->unsignedBigInteger('id_skm_answer')->nullable();
            $table->text('desc_skm_answer')->nullable();
            $table->unsignedBigInteger('id_skm_indicator')->nullable();
            $table->text('desc_skm_indicator')->nullable();
            $table->integer('value');
            $table->foreign('id_skm_result_header')->references('id')->on('tr_skm_result_header');
            $table->foreign('id_skm_question')->references('id')->on('ms_skm_question');
            $table->foreign('id_skm_answer')->references('id')->on('ms_skm_question_option');
            $table->foreign('id_skm_indicator')->references('id')->on('ms_skm_indicator');
        });
    }
    public function down() {
        Schema::dropIfExists('tr_skm_result_answer');
    }
};
