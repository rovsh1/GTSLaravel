<?php

use App\Shared\Support\Database\Schema\TranslationSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('r_transport_types', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->char('color', 7)->nullable();
        });

        TranslationSchema::create('r_transport_types', function (Blueprint $table) {
            $table->string('name');
        });
    }

    public function down(): void
    {
        TranslationSchema::dropIfExists('r_transport_types');
        Schema::dropIfExists('r_transport_types');
    }
};
