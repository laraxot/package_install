<?php

namespace XRA\Install\Controllers;

use Illuminate\Routing\Controller;

//--------   TRAITS   ---------------
use XRA\Extend\Traits\CrudSimpleTrait as CrudTrait;

class WelcomeController extends Controller{

    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome(){
    	$view=CrudTrait::getView();
        return view($view);
    }

}
