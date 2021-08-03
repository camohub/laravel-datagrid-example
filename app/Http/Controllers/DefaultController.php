<?php

namespace App\Http\Controllers;

use App\Models\Entities\Article;
use Camohub\LaravelDatagrid\Column;
use Camohub\LaravelDatagrid\Datagrid;
use Faker\Factory;
use Illuminate\Support\Str;


class DefaultController extends Controller
{
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		$grid = $this->getBaseDatagrid();

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
		$grid = new Datagrid(Article::with('user')->select('articles.*'));

		$grid->setJSFilterTimeout(500);

		$grid->addColumn('id')
			->setOutherTitleClass('text-center')
			->setOutherClass(function() { return 'colId text-center'; });

		$grid->addColumn('title')
			->setSort()
			->setFilter(function($model, $value) {
				return $model->where('title', 'like', "%$value%");
			})
			->setFilterRender(function($column) {
				return "<input type='text' 
							class='form-control chgrid-filter' 
							name='{$column->filterParamName}'  
							value='{$column->filterValue}' 
							title='Press enter to send filter request.'>";
			})
			->setSubmitOnEnter();

		$grid->addColumn('created_at', 'Created')
			->setRender(function($value, $row) {
				return '<b>' . $value->format('d.m.Y') . '</b>';
			})/*
			->setFilterRender(function ($column) {
				//dd($column->filterValue);
				return "<div class='' style='width: 250px;'>
								<input name='{$column->filterParamName}[]' 
									value='" . ($column->filterValue ? ($column->filterValue[0] ?? '') : '') ."'
									type='text' 
									class='form-control chgrid-filter' 
									style='display: block; width: 49%; float: left'>
								<input name='{$column->filterParamName}[]' 
									value='" . ($column->filterValue ? ($column->filterValue[1] ?? '')  : '') ."'
									type='text' 
									class='form-control chgrid-filter' 
									style='display: block; width: 49%; float: left; margin-left: 2%;'>
						</div>";
			})*/
			->setJSFilterPattern('\d{2}\.\d{2}\.\d{4}')
			->setFilter(function($model, $value) {
				//dd($value, new \DateTime($value), (new \DateTime($value))->format('d.m.Y'));
				$dateFrom = new \DateTimeImmutable($value);
				$dateTo = $dateFrom->modify('+1 day');
				return $model->where('created_at', '>', $dateFrom)
					->where('created_at', '<', $dateTo);
			})
			->setNoEscape()
			->setSort();

		$grid->addColumn('user.name', 'User')
			->setFilter(function($model, $value) {
				return $model->join('users', 'articles.user_id', '=', 'users.id')
					->where('users.name', 'like', "%$value%");
			});

		$grid->addColumn('user.roles', 'Roles')
			->setFilter(function($model, $value) {
				return $model->join('users', 'articles.user_id', '=', 'users.id')
					->join('users_roles', 'users.id', '=', 'users_roles.user_id')
					->join('roles', 'users_roles.role_id', '=', 'roles.id')
					->where('roles.name', 'like', "%$value%");
			})
			->setRender(function($value, $row) {
				return $value->map( function($value) { return $value->name; } )->join(', ');
			});

		$grid->addColumn('visible', 'Visible')
			->setOutherClass(function($value, $row) {
				return $value ? 'bg-primary text-center' : 'bg-danger text-center';
			});

		$grid->addColumn('', '', Column::TYPE_CUSTOM)
			->setNoEscape()
			->setOutherClass(function() { return 'colActions'; })
			->setRender(function($value, $row) {
				return '<a href="' . route('edit', ['id' => $row->id]) . '" class="fa fa-pencil"></a> 
					<a href="' . route('visibility', ['id' => $row->id]) . '" class="fa ' . ($row->visible ? 'fa-eye' : 'fa-minus-circle') . '"></a> 
					<a href="' . route('delete', ['id' => $row->id]) . '" class="text-danger fa fa-trash"></a>';
			});

		return $grid;

	}
}