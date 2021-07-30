<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Articles extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('articles', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->collation = 'utf8mb4_slovak_ci';
			$table->id();
			$table->unsignedBigInteger('user_id');
			$table->string('meta_desc', 255);
			$table->string('title', 255);
			$table->string('slug', 255);
			$table->text('perex');
			$table->longText('content');
			$table->boolean('visible')->default(FALSE);
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
		});


		Schema::create('articles_categories', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->id();
			$table->unsignedBigInteger('article_id');
			$table->unsignedBigInteger('category_id');

			$table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade')->onUpdate('cascade');
			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//Schema::drop('articles_categories');
		//Schema::drop('articles');
	}
}
