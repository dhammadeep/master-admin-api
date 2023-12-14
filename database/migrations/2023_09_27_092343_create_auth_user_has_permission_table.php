<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        Schema::create('auth_user_has_permission', function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger(config('permission.column_names.pivot_permission'));

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'auth_user_has_permission_model_id_model_type_index');

            $table->foreign(config('permission.column_names.pivot_permission'))
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(
                [config('permission.column_names.pivot_permission'), $columnNames['model_morph_key'], 'model_type'],
                'user_has_permissions_permission_model_type_primary'
            );
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_user_has_permission');
    }
};
