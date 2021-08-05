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
		<div class="container-fluid">
			<div class="row">
				<h1 id="header" class="col-12 col-md-9 translate translateInit">
					Camohub Laravel Datagrid
				</h1>
				<div class="col-md-3 d-none d-md-block text-right">
					<a class="github-button" href="https://github.com/camohub/laravel-datagrid" data-color-scheme="no-preference: light; light: light; dark: light;" data-size="large" aria-label="Star camohub/laravel-datagrid on GitHub">Star</a>
					<a class="github-button" href="https://github.com/camohub/laravel-datagrid/discussions" data-color-scheme="no-preference: light; light: light; dark: light;" data-size="large" aria-label="Discuss camohub/laravel-datagrid on GitHub">Discuss</a>
				</div>
				<h2 class="col-12 col-md-3 translate translateInit">examples</h2>

				<ul id="top-menu" class="col-12 col-md-9">
					<li><a href="{{route('index')}}" @if(request()->routeIs('index')) class="active" @endif>Basic</a></li>
					<li><a href="{{route('ajax')}}" @if(request()->routeIs('ajax')) class="active" @endif>Ajax</a></li>
					<li><a href="{{route('date-pickers')}}" @if(request()->routeIs('date-pickers')) class="active" @endif>Date pickers</a></li>
					<li><a href="{{route('documentation')}}" @if(request()->routeIs('documentation')) class="active" @endif>Documentation</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="container-fluid slim">
		<div class="row">
			<div id="main" class="translateInit translate-2 col-12">

				@yield('content')

			</div>
		</div>
	</div>

	<div id="footerPusher"></div>

</div>


<script type="text/javascript" src="{{mix('js/app.js')}}"></script>

{{--<script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>--}}


@yield('scripts')

@endsection