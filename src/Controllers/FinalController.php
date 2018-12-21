<?php

namespace XRA\Install\Controllers;

use Illuminate\Routing\Controller;
use XRA\Install\Helpers\EnvironmentManager;
use XRA\Install\Helpers\FinalInstallManager;
use XRA\Install\Helpers\InstalledFileManager;
use XRA\Install\Events\LaravelInstallerFinished;

//--------   TRAITS   ---------------
use XRA\Extend\Traits\CrudSimpleTrait as CrudTrait;

class FinalController extends Controller
{
    /**
     * Update installed file and display finished view.
     *
     * @param \XRA\Install\Helpers\InstalledFileManager $fileManager
     * @param \XRA\Install\Helpers\FinalInstallManager $finalInstall
     * @param \XRA\Install\Helpers\EnvironmentManager $environment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();
        $finalEnvFile = $environment->getEnvContent();

        event(new LaravelInstallerFinished);

        return view('install::finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));
    }
}
