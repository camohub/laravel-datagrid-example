<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;


class AjaxController extends Controller
{
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index(Request $request)
	{
		return $request->ajax()
			? view('ajax.ajax-datagrid', ['grid' => $this->getDatagrid()])
			: view('ajax.index', ['grid' => $this->getDatagrid()]);
	}
}
