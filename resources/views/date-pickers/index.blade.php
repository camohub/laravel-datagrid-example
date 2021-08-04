@extends('layouts.layout-full')

@section('content')

{{$grid->render()}}

<script>

window.addEventListener('DOMContentLoaded', function() {

	var filters = $('.chgrid-filter');
	var input1 = $('#chgrid-filter-created-at');
	var input2 = $('#chgrid-filter-created-at2');
	var input3 = $('#chgrid-filter-created-at3');
	var input4 = $('#chgrid-filter-created-at4');

	input1.daterangepicker({
		showDropdowns: true,
		minYear: 1970,
		maxYear: 2022,
		linkedCalendars: false,
		autoUpdateInput: false,
		locale: {
			format: 'DD.MM.YYYY'
		}
	}, function(start, end) {
		input1.val(start.format('DD.MM.YYYY') + ' - ' + end.format('DD.MM.YYYY'));
	});

	input2.daterangepicker({
		showDropdowns: true,
		minYear: 1970,
		maxYear: 2022,
		linkedCalendars: false,
		autoUpdateInput: false,
		locale: {
			format: 'YYYY-MM-DD'
		}
	}, function(start, end) {
		input2.val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
	});

	input3.daterangepicker({
		showDropdowns: true,
		minYear: 1970,
		maxYear: 2022,
		linkedCalendars: false,
		autoUpdateInput: false,
		singleDatePicker: true,
		locale: {
			format: 'YYYY-MM-DD'
		}
	}, function(date) {
		input3.val(date.format('YYYY-MM-DD'));
	});

	input4.daterangepicker({
		showDropdowns: true,
		minYear: 1970,
		maxYear: 2022,
		linkedCalendars: false,
		autoUpdateInput: false,
		singleDatePicker: true,
		locale: {
			format: 'YYYY-MM-DD'
		}
	}, function(date) {
		input4.val(date.format('YYYY-MM-DD'));
	});

	filters.on('apply.daterangepicker', function(e) {
		// jquery trigger('input') does not fire js input event.
		// dispatchEvent is not jquery method. It has to be called on js element.
		this.dispatchEvent(new Event('input'));
	});

});

</script>

<p>As you can see you can use the same database field more than one times in datagrid.
It is usefull if you need more than one filter or more format of the same field.
Datagrid ads the suffix for the fields with the same fieldName.

<p>Here is the code of getDatagrid() method from controller. Other code is the same as in basic example.
<pre class="prettyprint">
public function getDatagrid()
{
	$grid = new Datagrid(Article::with('user')->select('articles.*'));

	$grid->setJSFilterTimeout(500);

	$grid->addColumn('id')
		->setOutherTitleClass('text-center')
		->setOutherClass(function() { return 'colId text-center'; });

	$grid->addColumn('title');

	$grid->addColumn('created_at', 'Created')
		->setSort()
		->setRender(function($value, $row) {
			return $value->format('d.m.Y');
		})
		->setFilter(function($model, $value) {
			$explode = explode(' - ', $value);
			$dateFrom = new \DateTimeImmutable(trim($explode[0]));
			$dateTo = new \DateTimeImmutable(trim($explode[1]));
			return $model->where('created_at', '>=', $dateFrom)
				->where('created_at', '<=', $dateTo);
		})
		->setJSFilterPattern('\d{2}\.\d{2}\.\d{4} - \d{2}\.\d{2}.\d{4}');

	$grid->addColumn('created_at', 'Created 2')
		->setSort()
		->setRender(function($value, $row) {
			return $value->format('Y-m-d');
		})
		->setFilter(function($model, $value) {
			$explode = explode(' - ', $value);
			$dateFrom = new \DateTimeImmutable(trim($explode[0]));
			$dateTo = new \DateTimeImmutable(trim($explode[1]));
			return $model->where('created_at', '>=', $dateFrom)
				->where('created_at', '<=', $dateTo);
		})
		->setJSFilterPattern('\d{4}-\d{2}-\d{2} - \d{4}-\d{2}-\d{2}');

	$grid->addColumn('created_at', 'Created From')
		->setSort()
		->setRender(function($value, $row) {
			return $value->format('Y-m-d');
		})
		->setFilter(function($model, $value) {
			$dateFrom = new \DateTimeImmutable($value);
			return $model->where('created_at', '>=', $dateFrom);
		})
		->setJSFilterPattern('\d{4}-\d{2}-\d{2}');

	$grid->addColumn('created_at', 'Created To')
		->setSort()
		->setRender(function($value, $row) {
			return $value->format('Y-m-d');
		})
		->setFilter(function($model, $value) {
			$dateTo = new \DateTimeImmutable($value);
			return $model->where('created_at', '<=', $dateTo);
		})
		->setJSFilterPattern('\d{4}-\d{2}-\d{2}');

	return $grid;

}
</pre>

@endsection
