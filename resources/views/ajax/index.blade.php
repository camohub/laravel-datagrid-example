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

		axios.get('{{route('ajax')}}?' + ajaxChgridForm.serialize())
			.then(function(response) {
				ajaxDatagridWrapper.html(response.data);
			})
			.catch(function(response)
			{
				console.log(response);
			});
	});
});

</script>

Controller code looks like
<pre class="prettyprint">
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



		return $grid;
	}
</pre>

The main difference is in the template code which looks like

<pre class="prettyprint">
@extends('layouts.layout-full')

@section('content')

<div id="ajax-datagrid">
	@include('ajax.ajax-datagrid')
</div>

<script>

// Here is the key part which switches the datagrid to ajax.
window.addEventListener('DOMContentLoaded', function(e) {

	var ajaxDatagridWrapper = $('#ajax-datagrid');

	ajaxDatagridWrapper.on('submit', '#chgrid-form', function(e) {
		e.preventDefault();

		// #chgrid-form is dynamic form. Always needs to have fresh instance.
		var ajaxChgridForm = ajaxDatagridWrapper.find('#chgrid-form');

		axios.get('{{route('ajax')}}?' + ajaxChgridForm.serialize())
			.then(function(response) {
				ajaxDatagridWrapper.html(response.data);
			}).catch(function(response) {
				console.log(response);
			});
	});
});

</script>

@endsection
</pre>



@endsection
