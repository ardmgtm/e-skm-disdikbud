<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('vl_skm_question_type', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20);
        });
    }
    public function down() {
        Schema::dropIfExists('vl_skm_question_type');
    }
};
