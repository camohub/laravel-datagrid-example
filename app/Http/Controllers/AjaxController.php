<?php

namespace App\Http\Controllers;

use App\Models\Entities\Article;
use Camohub\LaravelDatagrid\Column;
use Camohub\LaravelDatagrid\Datagrid;
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


	public function getDatagrid()
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
							data-submitOnEnter='1'
							title='Press enter to send filter request.'>";
			})
			->setSubmitOnEnter();

		$grid->addColumn('created_at', 'Created')
			->setRender(function($value, $row) {
				return '<b>' . $value->format('d.m.Y') . '</b>';
			})
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
			})
			->setSelectFilter([0 => 'hidden', 1 => 'active'], 'all')
			->setFilter(function ($model, $value) {
				return $model->where('visible', $value);
			})
			->setRender(function ($value, $row) {
				return $value === 0 ? 'hidden' : 'active';
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
