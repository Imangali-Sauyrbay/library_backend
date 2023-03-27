<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::addEnumType('token_device_type', [
            'mobile',
            'tablet',
            'desktop',
            'other',
        ]);

        Schema::create('meta_personal_access_tokens', function (Blueprint $table) {
            $table->foreignId('personal_access_token_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();
            $table->ipAddress('ip');
            $table->string('user_agent', 500)->nullable();
            $table->string('platform_name', 100)->nullable();
            $table->string('browser_family', 100)->nullable();
            $table->string('device_name');
            $table->customTypedColumn('token_device_type', 'device_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta_personal_access_tokens');
        DB::dropType('token_device_type');
    }
};
