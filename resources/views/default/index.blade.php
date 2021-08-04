@extends('layouts.layout-full')

@section('content')

{{$grid->render()}}

<pre class="prettyprint">
public function getBaseDatagrid()
{
	$grid = new Datagrid(Article::with('user')->select('articles.*'));

	$grid->setJSFilterTimeout(500);

	$grid->addColumn('id', 'ID');

	$grid->addColumn('title')
		->setSort()
		->setFilter(function($model, $value) {
			return $model->where('title', 'like', "%$value%");
		})
		->setSubmitOnEnter();  // This option prevents filtering before user hits enter.

	$grid->addColumn('created_at', 'Created')
		->setSort()
		->setRender(function($value, $row) {
			return '&lt;b&gt;' . $value->format('d.m.Y') . '&lt;/b&gt;';
		})
		->setNoEscape()
		->setFilter(function($model, $value) {
			$dateFrom = new \DateTimeImmutable($value);
			$dateTo = $dateFrom->modify('+1 day');
			return $model->where('created_at', '>', $dateFrom)
				->where('created_at', '<', $dateTo);
		})
		->setJSFilterPattern('\d{2}\.\d{2}\.\d{4}')  // Regexp pattern check before filtering.;

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
		});

	$grid->addColumn('', '', Column::TYPE_CUSTOM)
		->setNoEscape()
		->setOutherClass(function() { return 'colActions'; })
		->setRender(function($value, $row) {
			return '&lt;a href="' . route('edit', ['id' => $row->id]) . '" class="fa fa-pencil"&gt;&lt;/a&gt;
				&lt;a href="' . route('visibility', ['id' => $row->id]) . '" class="fa fa-eye"&gt;&lt;/a&gt;
				&lt;a href="' . route('delete', ['id' => $row->id]) . '" class="text-danger fa fa-trash"&gt;&lt;/a&gt;';
		});

	return $grid;

}
</pre>

@endsection
