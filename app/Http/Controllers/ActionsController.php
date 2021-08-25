<?php

namespace App\Http\Controllers;

use App\Models\Entities\Article;


class ActionsController extends Controller
{

	public function edit($id)
	{
		flash('Article has been updated. This is only example message from the sever.')->success();
		return back();
	}

	public function visibility($id)
	{
		$article = Article::find($id);
		$article->visible = !$article->visible;
		$article->save();

		flash('Article visibility has been updated. <br>All data changes will be set to default values again every hour.')->success();
		return back();
	}

	public function delete($id)
	{
		$article = Article::find((int)$id);
		$article->delete();

		flash('Article has been deleted. <br>All data changes will be set to default values again every hour.')->success();
		return back();
	}
}
