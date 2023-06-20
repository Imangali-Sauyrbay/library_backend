<?php

use App\Services\ProvideModelsService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserAuth\Entities\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registration_links', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid');

            $table->integer('use_count');

            $table->timestamp('expires');

            $table->foreignIdFor(ProvideModelsService::getLibraryClass())
            ->constrained()->cascadeOnDelete();

            $table->foreignIdFor(Role::class)
            ->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registration_links');
    }
};
