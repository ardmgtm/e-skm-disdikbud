<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('tr_skm_result_header', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_skm_header');
            $table->unsignedBigInteger('id_service');
            $table->dateTime('timestamps');
            $table->foreign('id_skm_header')->references('id')->on('ms_skm_header');
            $table->foreign('id_service')->references('id')->on('ms_service');
        });
    }
    public function down() {
        Schema::dropIfExists('tr_skm_result_header');
    }
};
