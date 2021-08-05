@extends('layouts.layout-full')

@section('content')

<div id="ajax-datagrid">
	@include('ajax.ajax-datagrid')
</div>

<script>

window.addEventListener('DOMContentLoaded', function(e) {

	var ajaxDatagridWrapper = $('#ajax-datagrid');

	ajaxDatagridWrapper.on('submit', '#chgrid-form', function(e) {
		e.preventDefault();

		// #chgrid-form is dynamic form. Always needs to have fresh instance.
		var ajaxChgridForm = ajaxDatagridWrapper.find('#chgrid-form');
		var url = '{{route('ajax')}}?' + ajaxChgridForm.serialize();

		axios.get(url)
			.then(function(response) {
				ajaxDatagridWrapper.html(response.data);
				window.history.pushState({}, '', url);
			})
			.catch(function(response) {
				console.log(response);
			});
	});


	$('#ajax-datagrid').on('click', '.fa-trash', function(e) {
		return confirm('Are you sure you want to delete this article?');
	});
});

</script>

<p>The code will need little javascript in the template file. Controller code looks like

<pre class="prettyprint">
&lt;?php

namespace App\Http\Controllers;

use App\Models\Entities\Article;
use Camohub\LaravelDatagrid\Column;
use Camohub\LaravelDatagrid\Datagrid;
use Illuminate\Http\Request;


class AjaxController extends Controller
{
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

		...  // The same code as in basic example

		return $grid;
	}
}
</pre>

<p>The main difference is in the template code which looks like

<pre class="prettyprint">
&commat;extends('layouts.layout-full')

&commat;section('content')

&lt;div id="ajax-datagrid"&gt;
	&commat;include('ajax.ajax-datagrid')
&lt;/div&gt;

&lt;script&gt;

// Here is the key part which switches the datagrid to ajax.
window.addEventListener('DOMContentLoaded', function(e) {

	var ajaxDatagridWrapper = $('#ajax-datagrid');

	ajaxDatagridWrapper.on('submit', '#chgrid-form', function(e) {
		e.preventDefault();

		// #chgrid-form is dynamic form. Always needs to have fresh instance.
		var ajaxChgridForm = ajaxDatagridWrapper.find('#chgrid-form');
		var url = '{{route('ajax')}}?' + ajaxChgridForm.serialize();

		axios.get(url)
			.then(function(response) {
				ajaxDatagridWrapper.html(response.data);
				window.history.pushState({}, '', url);
			})
			.catch(function(response) {
				console.log(response);
			});
	});
});

&lt;/script&gt;

&commat;endsection
</pre>



@endsection
