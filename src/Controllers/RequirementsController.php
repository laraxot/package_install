<?php



namespace XRA\Install\Controllers;

use Illuminate\Routing\Controller;
use XRA\Extend\Services\ThemeService;
//--------   TRAITS   ---------------
//--- services
use XRA\Install\Helpers\RequirementsChecker;

class RequirementsController extends Controller
{
    /**
     * @var RequirementsChecker
     */
    protected $requirements;

    /**
     * @param RequirementsChecker $checker
     */
    public function __construct(RequirementsChecker $checker)
    {
        $this->requirements = $checker;
    }

    /**
     * Display the requirements page.
     *
     * @return \Illuminate\View\View
     */
    public function requirements()
    {
        $phpSupportInfo = $this->requirements->checkPHPversion(
            config('install.core.minPhpVersion')
        );
        $requirements = $this->requirements->check(
            config('install.requirements')
        );
        $view = ThemeService::getView(); //'install::requirements'
        return view($view, \compact('requirements', 'phpSupportInfo'));
    }
}
