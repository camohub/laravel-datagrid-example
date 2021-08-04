<?php

namespace App\Http\Controllers;

use App\Models\Entities\Article;
use Camohub\LaravelDatagrid\Column;
use Camohub\LaravelDatagrid\Datagrid;


class DocumentationController extends Controller
{

	public function index()
	{
		return view('documentation.index');
	}
}
