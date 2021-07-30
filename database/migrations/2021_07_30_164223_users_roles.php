<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersRoles extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->collation = 'utf8mb4_slovak_ci';
			$table->id();
			$table->string('name', '25');
			$table->softDeletes();
		});


		Schema::create('users_roles', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->collation = 'utf8mb4_slovak_ci';
			$table->id();
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('role_id');
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
			$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//Schema::drop('users_roles');
		//Schema::drop('roles');
	}
}
