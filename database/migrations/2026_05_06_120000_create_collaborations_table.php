<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('collaborations', function (Blueprint $table) {
            $table->id();
            $table->string('logo');
            $table->string('organization_name');
            $table->string('subtitle')->nullable();
            $table->longText('description');
            $table->string('representative_designation')->nullable();
            $table->string('representative_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collaborations');
    }
};
