@extends('layouts.base')

@section('body')

@section('scripts')
	{{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="{$basePath}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!--script src="{$basePath}/js/netteForms.js"></script-->
	<script src="{$basePath}/bower_components/nette-live-form-validation/live-form-validation.js"></script>
	<script src="{$basePath}/bower_components/nette.ajax.js/nette.ajax.js"></script>--}}
@endsection

<div id="wrapper">

	<div id="header-wrapper" class="">
		<div class="container">
			<div class="row">
				<h1 id="header" class="col-12 translate translateInit">Camohub Laravel Datagrid</h1>
				<h2 class="col-12 col-md-3 translate translateInit">examples</h2>

				<ul id="top-menu" class="col-12 col-md-9">
					<li><a href="{{route('index')}}">Basic</a></li>
					<li><a href="{{route('date-pickers')}}">Date pickers</a></li>
					<li><a href="{{route('ajax')}}">Ajax</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">

			<div id="main" class="translateInit translate-2 col-12">

				@yield('content')

			</div>

		</div>
	</div>

	<div id="footerPusher"></div>

</div>


<script type="text/javascript" src="{{mix('js/app.js')}}"></script>

@yield('scripts')

@endsection