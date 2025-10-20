<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('vl_skm_indicator', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('indicator_name', 100);
        });
    }
    public function down() {
        Schema::dropIfExists('vl_skm_indicator');
    }
};
