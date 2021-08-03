<?php

namespace App\Http\Controllers;

use App\Models\Entities\Article;
use Camohub\LaravelDatagrid\Column;
use Camohub\LaravelDatagrid\Datagrid;
use Faker\Factory;
use Illuminate\Support\Str;


class ActionsController extends Controller
{

	public function edit($id)
	{
		return redirect()->route('index');
	}

	public function visibility($id)
	{
		return redirect()->route('index');
	}

	public function delete($id)
	{
		return redirect()->route('index');
	}
}
