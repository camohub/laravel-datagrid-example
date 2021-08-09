@extends('layouts.layout-slim')

@section('content')

<p>All examples are availabel on <a href="https://github.com/camohub/laravel-datagrid">Github</a>.

<h2>Installation</h2>

<pre class="prettyprint">
composer install camohub/laravel-datagrid
</pre>

		<p>Datagrid constructor accepts one parameter which can be
			Illuminate\Database\<b>Eloquent\Builder</b> or Illuminate\Database\<b>Query\Builder</b> as datasource.


		<p>This package is based on form GET request. Whole table is a form element.
			You can simply catch submit event if you need.
			Empty inputs are disabled on submit by js and automatically removed from url.

		<h3>Datagrid contains this groups of inputs</h3>

		<ul>
			<li><b>sort inputs</b> - every sortalbe field has its own hidden input.
				After click on the sortable column js sets the hidden input value.
				If value is empty hidden input is disabled.

			<li><b>filter inputs</b> - filter inputs triggers form submit on
				<b><a href="https://developer.mozilla.org/en-US/docs/Web/API/HTMLElement/input_event">input event</a></b>.
				There is also timeout as throttling to wait for another input events.
				This timeout can be set in php grid definition globally by <b>setJSFilterTimeout()</b>.
				Timeout can be replaced by setFilterOnEnter() on column level which stops auto submit and will wait for hit enter.

			<li><b>perPage select</b> - onchange event triggers form submit immediately.

			<li><b>page</b> - paginator page param is also as hidden input.
		</ul>

		<p>This form submit implementation has one little disadvantage.
			It removes all other GET parameters from url. But it is easy to fix it.
			You can set all necessary GET parameters via <b>$grid->addGetParam('name')</b>.


		<h2>Example</h2>

		<p>Controller code implements a method which returns datagrid instance.

<pre class="prettyprint">
&lt;?php

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
		$grid = new Datagrid(Article::with('user'));

		$grid->addColumn('id')
			->setSort();

		$grid->addColumn('title')
			->setSort()
			->setFilter(function($model, $value) {
				return $model->where('title', 'like', "%$value%");
			});

		$grid->addColumn('created_at', 'Created')
			->setSort();
			// Valid javascript regexp pattern.
			->setJSFilterPattern('\d{2}\.\d{2}\.\d{4}')
			->setFilter(function($model, $value) {
				// This is bacause database contains also the time.
				$dateFrom = (new \DateTimeImmutable($value))->setTime(0, 0);
				$dateTo = $dateFrom->modify('+1 day');

				return $model->where('created_at', '>', $dateFrom)
					->where('created_at', '<', $dateTo);
			})
			->setRender(function($value, $item) {
				return '&lt;b&gt;' . $value->format('d.m.Y H:i') . '&lt;/b&gt;';
			})
			// Turns off template html escaping.
			->setNoEscape()

		// Select filter
		$grid->addColumn('visible', 'Visible')
			->setOutherClass(function($value, $row) {
				return $value ? 'bg-primary text-center' : 'bg-danger text-center';
			})
			->setSelectFilter([0 => 'hidden', 1 => 'active'], 'all')
			->setFilter(function ($model, $value) {
				return $model->where('visible', $value);
			});

		// HasOne relation
		$grid->addColumn('user.name', 'User');

		// ManyHasMany relation
		$grid->addColumn('user.roles', 'Roles')
			->setRender(function($value, $item) {
				return $value->map( function($value) { return $value->name; } )->join(', ');
			});

		// TYPE_CUSTOM intended for content not related to model.
		$grid->addColumn('', '', Column::TYPE_CUSTOM)
			->setNoEscape()
			->setRender(function($value, $item) {
				return '
					&lt;a href="' . route('admin.articles.edit', ['id' => $item->id]) . '"&gt;edit&lt;/a&gt;
					&lt;a href="' . route('admin.articles.visibility', ['id' => $item->id]) . '"&gt;visibility&lt;/a&gt;
					&lt;a href="' . route('admin.articles.delete', ['id' => $item->id]) . '" class="text-danger"&gt;delete&lt;/a&gt;
				';
			});

		return $grid;
	}
}
</pre>

		<p>The template code is below.

<pre class="prettyprint">
&lcub;&lcub;$grid->render()&rcub;&rcub;
</pre>


		<h2>Options</h2>

		<p>There are two groups of options.

		<p>Global datagrid options and column specific options.

		<h3>Datagrid options</h3>

		<ul>
			<li><b>setDefaultPerPage()</b> - yes it really sets the default perPage items number.

			<li><b>setPerPage()</b> - expects array with possible dropdown options like [10, 25, 50, 100].

			<li><b>setOnEachSide()</b> - it is the wrapper above the Laravel pagination onEachSide() option.

			<li><b>setTableClass()</b> - default is 'table table-striped table-hover table-bordered';

			<li><b>setJSFilterTimeout()</b> - sets javascript timeout on input event. Default is 250ms.

			<li><b>setSubmitOnEnter()</b> - prevent submit on input event and will wait for hit enter key to submit.
				This option is possible to set for the whole grid or for one column.
				Does not affect sorting, pagination and perPage select. They are still automatically submited.

			<li><b>setGetParams()</b> - form submit removes all GET params from url which are not
				the part of the form. Request will contain only form inputs as GET prameters.
				setGetParams('paramName') will include all necessary GET params
				which should be included in all datagrid GET requests.
		</ul>

		<h3>Column options</h3>

		<ul>
			<li><b>setRender()</b> - accepts callback with two parameters - value and row.

			<li><b>setSort()</b> - accepts callback or no parameters. Without parameters make simple sort according field name.
				Callback gets two params - queryBuilder and sort value.

			<li><b>setFilter()</b> - accepts callback with two parameters - queryBuilder and filter value.
				Filter callback is not called if filter value is NULL or empty string. Other values like 0 will call the filter.

			<li><b>setFilterSelect()</b> - expects associative array and options prompt parameter.
				This function renders select element in the filter field.
				Datagrid sets the selected option according the $key === $value condition. Ensure that you compare the same types.

			<li><b>setJSFilterPattern()</b> - accepts javascript regexp patterns as string. If value does not match
				the pattern validator will block the request and will add .text-danger class to input field.

			<li><b>setFilterOnEnter()</b> - alias for <b>setSubmitOnEnter()</b> - prevent submit on input event and will wait for hit enter key to submit.
				This option is possible to set for the whole grid or for one column.
				Does not affect sorting, pagination and perPage select. They are still automatically submited.

			<li><b>setFilterRender()</b> - allows you to render filter input manually.
			Be sure rendered filter input has css class <b>.chgrid-filter</b>.

			<li><b>setNoEscape()</b> - custom render wont be escaped. Template use &lcub;!! !!&rcub; instead of &lcub;&lcub; &rcub;&rcub;.

			<li><b>setOutherClass()</b> - accepts callback. Callback will be useful if you
				need to make some conditional styles for the field.
			Callback will get two parameters - <b>value</b> and <b>row</b> and has to return <b>string</b>.

			<li><b>setOutherTitleClass()</b> - accepts string value. Will set up css class of TH element with title.
		</ul>

@endsection
