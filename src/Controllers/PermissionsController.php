<?php

namespace XRA\Install\Controllers;

use Illuminate\Routing\Controller;
use XRA\Install\Helpers\PermissionsChecker;

//--------   TRAITS   ---------------
use XRA\Extend\Traits\CrudSimpleTrait as CrudTrait;


class PermissionsController extends Controller{

    /**
     * @var PermissionsChecker
     */
    protected $permissions;

    /**
     * @param PermissionsChecker $checker
     */
    public function __construct(PermissionsChecker $checker){
        $this->permissions = $checker;
    }

    /**
     * Display the permissions check page.
     *
     * @return \Illuminate\View\View
     */
    public function permissions(){
        $permissions = $this->permissions->check(
            config('install.permissions')
        );
        $view=CrudTrait::getView();//'install::permissions'
        return view($view, compact('permissions'));
    }
}
