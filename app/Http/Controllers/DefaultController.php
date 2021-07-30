<?php

namespace App\Http\Controllers;

use App\Models\Entities\Article;
use App\Models\User;
use Camohub\LaravelDatagrid\Column;
use Camohub\LaravelDatagrid\Datagrid;
use Illuminate\Http\Request;


class DefaultController extends Controller
{
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		//$grid = $this->getBaseDatagrid();

		dd(User::take(1)->with('articles')->get());
		dd(Article::take(1)->with('categories')->get());

		return view('default.index', ['grid' => $grid]);
	}


	public function datePickers()
	{
		return view('default.date-pickers');
	}


	public function ajax()
	{
		return view('default.ajax');
	}


	public function getBaseDatagrid()
	{
		$grid = new Datagrid(Article::with('user'));
		$grid->setJSFilterTimeout(1000);
		$grid->addColumn('id')
			->setSort()
			->setFilter(function($model, $value) {
				return $value ? $model->where('id', "%$value%") : $model;
			});

		$grid->addColumn('title')
			->setSort()
			->setJSFilterPattern('lara')
			->setFilter(function($model, $value) {
				return $value ? $model->where('title', 'like', "%$value%") : $model;
			})
			->setSubmitOnEnter();

		$grid->addColumn('created_at', 'Created')
			->setRender(function($value, $item) {
				return '<b>' . $value->format('d.m.Y H:i') . '</b>';
			})
			->setFilterRender(function ($column) {
				//dd($column->filterValue);
				return "<div class='' style='width: 250px;'>
								<input name='{$column->filterParamName}[]' 
									value='" . ($column->filterValue ? ($column->filterValue[0] ?? '') : '') ."'
									type='text' 
									class='form-control chgrid-filter' 
									style='display: inline-block; width: 49%;'>
								<input name='{$column->filterParamName}[]' 
									value='" . ($column->filterValue ? ($column->filterValue[1] ?? '')  : '') ."'
									type='text' 
									class='form-control chgrid-filter' 
									style='display: inline-block; width: 49%'>
						</div>";
			})
			->setFilter(function($model, $value) {
				return $model;
			})
			->setNoEscape()
			->setSort();

		$grid->addColumn('visible', 'Visible')
			->setOutherClass(function($value, $item) {
				return $value ? 'bg-success' : 'bg-danger';
			});

		$grid->addColumn('user.name', 'User');
		$grid->addColumn('user.roles', 'Roles')
			->setRender(function($value, $item) {
				return $value->map( function($value) { return $value->name; } )->join(', ');
			});

		$grid->addColumn('', '', Column::TYPE_CUSTOM)
			->setNoEscape()
			->setRender(function($value, $item) {
				return '
					<a href="' . route('edit', ['id' => $item->id]) . '">edit</a>
					<a href="' . route('visibility', ['id' => $item->id]) . '">visibility</a>
					<a href="' . route('delete', ['id' => $item->id]) . '" class="text-danger">visibility</a>
				';
			});

		return $grid;

	}


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
