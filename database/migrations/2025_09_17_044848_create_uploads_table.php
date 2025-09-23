<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');      // Original file name
            $table->string('file_path');      // Storage path
            $table->string('file_type')->nullable(); // mime type (image/png, pdf, etc.)
            $table->unsignedBigInteger('file_size')->nullable(); // size in bytes
            $table->unsignedBigInteger('user_id')->nullable();   // uploader (optional)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};
