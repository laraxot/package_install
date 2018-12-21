<?php

namespace XRA\Install\Controllers;

use Illuminate\Routing\Controller;
use XRA\Install\Helpers\DatabaseManager;

//--------   TRAITS   ---------------
use XRA\Extend\Traits\CrudSimpleTrait as CrudTrait;

class DatabaseController extends Controller
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * Migrate and seed the database.
     *
     * @return \Illuminate\View\View
     */
    public function database()
    {
        $response = $this->databaseManager->migrateAndSeed();
        return redirect()->route('LaravelInstaller::final')
                         ->with(['message' => $response]);
    }
}
