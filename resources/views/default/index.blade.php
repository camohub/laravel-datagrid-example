@extends('layouts.layout-full')

@section('content')

{{$grid->render()}}

<script>
	window.addEventListener('DOMContentLoaded', function(e) {

		$('#ajax-datagrid').on('click', '.fa-trash', function(e) {
			return confirm('Are you sure you want to delete this article?');
		});
	});
</script>

<p>Datagrid definition consists from columns definitions. Code for one column can look like this.
<pre class="prettyprint">
$grid->addColumn('title')
	->setSort()
	->setFilter(function($model, $value) {
		return $model->where('title', 'like', "%$value%");
	})
	->setSubmitOnEnter();

// ManyToMany example
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
</pre>

<p>The controller code for whole datagrid on this page is below.
<pre class="prettyprint">
&lt;?php

namespace App\Http\Controllers;

use App\Models\Entities\Article;
use Camohub\LaravelDatagrid\Column;
use Camohub\LaravelDatagrid\Datagrid;


class DefaultController extends Controller
{

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
			->setSubmitOnEnter();

		$grid->addColumn('created_at', 'Created')
			->setSort()
			->setJSFilterPattern('\d{2}\.\d{2}\.\d{4}')
			->setFilter(function($model, $value) {
				$dateFrom = new \DateTimeImmutable($value);
				$dateTo = $dateFrom->modify('+1 day');
				return $model->where('created_at', '>', $dateFrom)
					->where('created_at', '<', $dateTo);
			})
			->setRender(function($value, $row) {
				return '&lt;b&gt;' . $value->format('d.m.Y') . '&lt;/b&gt;';
			})
			->setNoEscape();

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
			->setOutherClass(function() { return 'colActions'; })
			->setRender(function($value, $row) {
				return '&lt;a href="' . route('edit', ['id' => $row->id]) . '" class="fa fa-pencil"&gt;&lt;/a&gt;
					&lt;a href="' . route('visibility', ['id' => $row->id]) . '" class="fa ' . ($row->visible ? 'fa-eye' : 'fa-minus-circle') . '"&gt;&lt;/a&gt;
					&lt;a href="' . route('delete', ['id' => $row->id]) . '" class="text-danger fa fa-trash"&gt;&lt;/a&gt;';
			})
			->setNoEscape();

		return $grid;

	}
}
</pre>

Template is code is really simple.
<pre class="prettyprint">
{{$grid->render()}}
</pre>

@endsection
