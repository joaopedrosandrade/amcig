<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->comment('1=ativo, 0=inativo');
            $table->timestamp('last_login_at')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->boolean('is_superadmin')->default(false)->comment('Superadmin bypassa verificações de permissão');
            
            // Foreign key
            $table->foreign('updated_by')->references('id')->on('admins')->onDelete('set null');
            
            // Indexes
            $table->index('status');
            $table->index('is_superadmin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropIndex(['status']);
            $table->dropIndex(['is_superadmin']);
            $table->dropColumn(['status', 'last_login_at', 'updated_by', 'is_superadmin']);
        });
    }
}
