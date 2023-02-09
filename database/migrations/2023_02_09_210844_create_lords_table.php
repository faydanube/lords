<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lords', function (Blueprint $table) {
            $table->id();
            $table->char('fingerprint', 32)->comment('瀏覽器指紋');
            $table->boolean('is_sync')->default(false)->comment('同步到統計表');
            $table->date('expired_at')->comment('過期日');
            $table->timestamps();
            $table->index('fingerprint');
            $table->index('expired_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lords');
    }
};
