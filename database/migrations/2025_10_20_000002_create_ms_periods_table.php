<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('ms_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->date('start_period');
            $table->date('end_period');
            $table->boolean('is_active')->default(1);
            $table->boolean('is_deleted')->default(0);
        });
    }
    public function down() {
        Schema::dropIfExists('ms_periods');
    }
};
