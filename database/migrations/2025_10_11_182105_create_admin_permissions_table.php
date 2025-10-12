<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('admin_id');
            $table->string('menu_key', 50);
            $table->boolean('can_view')->default(false);
            $table->boolean('can_create')->default(false);
            $table->boolean('can_update')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('admins')->onDelete('set null');
            
            // Unique constraint
            $table->unique(['admin_id', 'menu_key']);
            
            // Indexes
            $table->index('admin_id');
            $table->index('menu_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_permissions');
    }
}
