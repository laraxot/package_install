<?php
namespace XRA\Install\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//--- services
use XRA\Extend\Services\ThemeService;

class InstallController extends Controller
{
    public function index(Request $request)
    {
        if ($request->act=='routelist') {
            return ArtisanTrait::exe('route:list');
        }
        $view = ThemeService::getView();

        return view($view);
    }

    //end function
 //
}//end class
