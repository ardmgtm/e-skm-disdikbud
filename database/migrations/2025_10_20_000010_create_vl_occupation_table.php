<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('vl_occupation', function (Blueprint $table) {
            $table->id();
            $table->string('occupation_desc', 100);
        });
    }
    public function down() {
        Schema::dropIfExists('vl_occupation');
    }
};
