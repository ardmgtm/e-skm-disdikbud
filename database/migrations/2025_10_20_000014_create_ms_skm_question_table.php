<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('ms_skm_question', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_skm_header');
            $table->unsignedBigInteger('id_skm_question_type');
            $table->unsignedBigInteger('id_skm_indicator');
            $table->text('question');
            $table->dateTime('created_at');
            $table->unsignedBigInteger('created_by');
            $table->dateTime('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('id_skm_header')->references('id')->on('ms_skm_header');
            $table->foreign('id_skm_question_type')->references('id')->on('vl_skm_question_type');
            $table->foreign('id_skm_indicator')->references('id')->on('vl_skm_indicator');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }
    public function down() {
        Schema::dropIfExists('ms_skm_question');
    }
};
