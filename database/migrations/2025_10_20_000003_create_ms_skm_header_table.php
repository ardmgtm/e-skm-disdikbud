<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('ms_skm_header', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('id_period');
            $table->string('name', 100);
            $table->string('title', 100);
            $table->text('description');
            $table->boolean('is_locked')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->dateTime('created_at');
            $table->unsignedBigInteger('created_by');
            $table->dateTime('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('id_period')->references('id')->on('ms_periods');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }
    public function down() {
        Schema::dropIfExists('ms_skm_header');
    }
};
