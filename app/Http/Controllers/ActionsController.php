<?php

namespace App\Http\Controllers;

use App\Models\Entities\Article;


class ActionsController extends Controller
{

	public function edit($id)
	{
		flash('Article has been updated.')->success();
		return back();
	}

	public function visibility($id)
	{
		$article = Article::find($id);
		$article->visible = !$article->visible;
		$article->save();

		flash('Article visibility has been updated.')->success();
		return back();
	}

	public function delete($id)
	{
		$article = Article::find((int)$id);
		$article->delete();

		flash('Article has deleted. <br>All deleted articles will be automatically visible again in few minutes.')->success();
		return back();
	}
}
