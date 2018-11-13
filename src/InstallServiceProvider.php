<?php

namespace XRA\Install;

use Illuminate\Support\ServiceProvider;
use XRA\Extend\Traits\ServiceProviderTrait;

class InstallServiceProvider extends ServiceProvider{
    use ServiceProviderTrait;
    /**
   * Register the application services.
   *
   * @return void
   */
	public function register(){
		foreach (glob(__DIR__.'/Helpers/*.php') as $filename){
			$filename=str_replace('/',DIRECTORY_SEPARATOR,$filename);
			require_once($filename);
		}
	}
}
