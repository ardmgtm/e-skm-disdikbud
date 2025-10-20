<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('tr_respondent', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_skm_result_header');
            $table->boolean('gender');
            $table->integer('age');
            $table->unsignedBigInteger('id_education')->nullable();
            $table->string('name_education', 100)->nullable();
            $table->unsignedBigInteger('id_occupation')->nullable();
            $table->string('name_occupation', 100);
            $table->foreign('id_skm_result_header')->references('id')->on('tr_skm_result_header');
            $table->foreign('id_education')->references('id')->on('vl_education');
            $table->foreign('id_occupation')->references('id')->on('vl_occupation');
        });
    }
    public function down() {
        Schema::dropIfExists('tr_respondent');
    }
};
