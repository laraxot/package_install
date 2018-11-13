@extends('install::layouts.master')

@section('template_title')
    {{ trans('install::messages.composer.templateTitle') }}
@endsection

@section('title')
	{{--
    <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
	--}}
	<img src="{{ Theme::img_src('install::img/composer.png') }}" width="64">
    {{ trans('install::messages.composer.title') }}
@endsection

@section('container')
<pre>
   ______
  / ____/___  ____ ___  ____  ____  ________  _____
 / /   / __ \/ __ `__ \/ __ \/ __ \/ ___/ _ \/ ___/
/ /___/ /_/ / / / / / / /_/ / /_/ (__  )  __/ /
\____/\____/_/ /_/ /_/ .___/\____/____/\___/_/  
                    /_/
</pre>

@endsection