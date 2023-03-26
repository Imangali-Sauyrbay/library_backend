<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\UserAuth\Entities\User;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::addMorphType('access_token_type', [User::class]);

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tokenable_id');
            $table->customTypedColumn('access_token_type', 'tokenable_type');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
        DB::dropType('access_token_type');
    }
};
