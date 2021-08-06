<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ArticlesIndexes extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('articles', function (Blueprint $table) {
			$table->index('created_at');
			$table->index('updated_at');
			$table->index('deleted_at');
			$table->index('visible');
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
