<?php

use App\Services\Auto\Models\AutoMark;
use App\Services\Auto\Models\AutoModel;
use App\Services\User\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('autos', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('year');
            $table->unsignedInteger('mileage');
            $table->string('color');

            $table->foreignIdFor(User::class)->nullable()->constrained();
            $table->foreignIdFor(AutoModel::class)->constrained();
            $table->foreignIdFor(AutoMark::class)->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autos');
    }
};
