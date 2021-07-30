<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Categories extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->collation = 'utf8mb4_slovak_ci';
			$table->id();
			$table->unsignedBigInteger('parent_id')->nullable();
			$table->string('name', 25);
			$table->string('slug', 25);
			$table->unsignedSmallInteger('sort');
			$table->boolean('visible')->default(TRUE);
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('parent_id')->references('id')->on('categories')->onDelete('restrict')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//Schema::drop('categories');
	}
}
