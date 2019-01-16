<?php



namespace XRA\Install\Controllers;

use Illuminate\Routing\Controller;
//--------   TRAITS   ---------------
//--- services
use XRA\Extend\Services\ThemeService;

class WelcomeController extends Controller
{
    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        $view = ThemeService::getView();

        return view($view);
    }
}
