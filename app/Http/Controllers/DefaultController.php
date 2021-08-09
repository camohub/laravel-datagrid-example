<?php

namespace App\Http\Controllers;


class DefaultController extends Controller
{
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		$grid = $this->getDatagrid();

		return view('default.index', ['grid' => $grid]);
	}
}
