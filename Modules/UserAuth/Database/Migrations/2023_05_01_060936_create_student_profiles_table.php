<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('faculty')->nullable();
            $table->string('department')->nullable();
            $table->string('group')->nullable();

            $table->string('IIN')->nullable();

            $table->timestamp('admission_at')->nullable();
            $table->timestamp('graduation_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_profiles');
    }
};
