<?php

namespace App\Http\Controllers;

use App\Models\Entities\Article;
use Camohub\LaravelDatagrid\Column;
use Camohub\LaravelDatagrid\Datagrid;


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


	public function getDatagrid()
	{
		$grid = new Datagrid(Article::with('user')->select('articles.*'));

		$grid->setJSFilterTimeout(500);

		$grid->setDefaultSort(function ($model) {
			return $model->orderBy('id', 'desc');
		});

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
			->setSort()
			->setJSFilterPattern('\d{4}-\d{2}-\d{2}')
			->setFilter(function($model, $value) {
				$dateFrom = new \DateTimeImmutable($value);
				$dateTo = $dateFrom->modify('+1 day');
				return $model->where('articles.created_at', '>', $dateFrom)
					->where('articles.created_at', '<', $dateTo);
			})
			->setRender(function($value, $row) {
				return '<b>' . $value->format('Y-m-d') . '</b>';
			})
			->setNoEscape();

		$grid->addColumn('user.name', 'User')
			->setSort(function($model, $value) {
				return $model->join('users', 'articles.user_id', '=', 'users.id')
					->orderBy('users.name', $value);
			})
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
			->setSort()
			->setOutherClass(function($value, $row) {
				return $value ? 'bg-primary text-center' : 'bg-danger text-center';
			})
			->setSelectFilter([0 => 'hidden', 1 => 'active'], 'all')
			->setFilter(function ($model, $value) {
				return $model->where('visible', $value);
			})
			->setRender(function ($value, $row) {
				return $value === 0 ? 'hidden' : 'active';
			});

		$grid->addColumn('', '', Column::TYPE_CUSTOM)
			->setOutherClass(function() { return 'colActions'; })
			->setRender(function($value, $row) {
				return '<a href="' . route('edit', ['id' => $row->id]) . '" class="fa fa-pencil"></a> 
					<a href="' . route('visibility', ['id' => $row->id]) . '" class="fa ' . ($row->visible ? 'fa-eye' : 'fa-minus-circle') . '"></a> 
					<a href="' . route('delete', ['id' => $row->id]) . '" class="text-danger fa fa-trash"></a>';
			})
			->setNoEscape();

		return $grid;

	}
}
