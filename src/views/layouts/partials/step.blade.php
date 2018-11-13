<ul class="step">
	<li class="step__divider"></li>
	<li class="step__item {{ isActive('LaravelInstaller::final') }}">
		<i class="step__icon fa fa-server" aria-hidden="true"></i>
	</li>
	<li class="step__divider"></li>
	<li class="step__item {{ isActive('LaravelInstaller::environment')}} {{ isActive('LaravelInstaller::environmentWizard')}} {{ isActive('LaravelInstaller::environmentClassic')}}">
		@if(Request::is('install/environment') || Request::is('install/environment/wizard') || Request::is('install/environment/classic') )
			<a href="{{ route('LaravelInstaller::environment') }}">
				<i class="step__icon fa fa-cog" aria-hidden="true"></i>
			</a>
		@else
			<i class="step__icon fa fa-cog" aria-hidden="true"></i>
		@endif
	</li>
	<li class="step__divider"></li>
	<li class="step__item {{ isActive('LaravelInstaller::composer') }}">
		@if(Request::is('install/permissions') || Request::is('install/environment') || Request::is('install/environment/wizard') || Request::is('install/environment/classic') )
			<a href="{{ route('LaravelInstaller::composer') }}">
				{{--<img src="{{ Theme::img_src('install::img/composer.png') }}" width="32">
				   --}}
				<i class="step__icon fa fa-magic" aria-hidden="true"></i>
			</a>
		@else
			{{--  <img src="{{ Theme::img_src('install::img/composer.png') }}" width="32"> --}}
			{{--  <i class="step__icon fa fa-key" aria-hidden="true"></i> --}}
			<i class="step__icon fa fa-magic" aria-hidden="true"></i>
		@endif
	</li>


	<li class="step__divider"></li>
	<li class="step__item {{ isActive('LaravelInstaller::permissions') }}">
		@if(Request::is('install/permissions') || Request::is('install/environment') || Request::is('install/environment/wizard') || Request::is('install/environment/classic') )
			<a href="{{ route('LaravelInstaller::permissions') }}">
				<i class="step__icon fa fa-key" aria-hidden="true"></i>
			</a>
		@else
			<i class="step__icon fa fa-key" aria-hidden="true"></i>
		@endif
	</li>
	<li class="step__divider"></li>
	<li class="step__item {{ isActive('LaravelInstaller::requirements') }}">
		@if(Request::is('install') || Request::is('install/requirements') || Request::is('install/permissions') || Request::is('install/environment') || Request::is('install/environment/wizard') || Request::is('install/environment/classic') )
			<a href="{{ route('LaravelInstaller::requirements') }}">
				<i class="step__icon fa fa-list" aria-hidden="true"></i>
			</a>
		@else
			<i class="step__icon fa fa-list" aria-hidden="true"></i>
		@endif
	</li>
	<li class="step__divider"></li>
	<li class="step__item {{ isActive('LaravelInstaller::welcome') }}">
		@if(Request::is('install') || Request::is('install/requirements') || Request::is('install/permissions') || Request::is('install/environment') || Request::is('install/environment/wizard') || Request::is('install/environment/classic') )
			<a href="{{ route('LaravelInstaller::welcome') }}">
				<i class="step__icon fa fa-home" aria-hidden="true"></i>
			</a>
		@else
			<i class="step__icon fa fa-home" aria-hidden="true"></i>
		@endif
	</li>
	<li class="step__divider"></li>
</ul>