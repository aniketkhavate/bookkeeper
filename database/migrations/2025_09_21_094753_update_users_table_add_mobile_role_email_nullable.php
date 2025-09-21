<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableAddMobileRoleEmailNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();

            // Ensure mobile exists and is unique
            if (!Schema::hasColumn('users', 'mobile')) {
                $table->string('mobile', 255)->unique()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Make email NOT NULL again
            $table->string('email')->nullable(false)->change();

            // Drop mobile if added
            if (Schema::hasColumn('users', 'mobile')) {
                $table->dropColumn('mobile');
            }
        });
    }
}
