<?php

namespace XRA\Install\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Enteweb;
use App;
use Artisan;
use Auth;

/*
* Models
*/
use LiveUsers\Models\User;
use LiveUsers\Models\Permission;

class InstallController extends Controller
{
    public function show($locale)
    {
        // Show the installation form
        if (!Enteweb::checkInstalled()) {
            // Set the locale
            App::setLocale($locale);

            return view('install::index');
        } else {
            return redirect()->route('enteweb.dashboard')->with('warning', trans('enteweb.already_installed'));
        }
    }

    public function installConfig($locale, Request $request)
    {
        if (!Enteweb::checkInstalled()) {
            // Install Enteweb

            $this->validate($request, [
              'USER_NAME' => 'required',
              'USER_PASSWORD' => 'required|min:6|confirmed',
              'USER_EMAIL' => 'required',
              'ADMINISTRATOR_ROLE_NAME' => 'required',
              'DEFAULT_ROLE_NAME' => 'required',
              'DB_HOST' => 'required',
              'DB_PORT' => 'required',
              'DB_DATABASE' => 'required',
              'DB_USERNAME' => 'required',
          ]);

            $file_location = base_path() . '/.env';
            $env = fopen($file_location, "w") or die("Impossibile aprire il file!");
            foreach ($request->all() as $key => $data) {
                if ($key != '_token' and $key != 'USER_PASSWORD_confirmation') {
                    fwrite($env, $key . "='" . $data . "'\n");
                }
            }
            $default = "\nREDIS_HOST=127.0.0.1\nREDIS_PASSWORD=null\nREDIS_PORT=6379\n\nPUSHER_KEY=\nPUSHER_SECRET=\nPUSHER_APP_ID=\n\nBROADCAST_DRIVER=log\nCACHE_DRIVER=file\nSESSION_DRIVER=file\nQUEUE_DRIVER=sync\n\nAPP_ENV=local\nAPP_KEY=" . env('APP_KEY') . "\nAPP_DEBUG=true\nAPP_LOG_LEVEL=debug\nAPP_URL=" . url('/') . "\n";
            fwrite($env, $default);
            fclose($env);

            return redirect()->route('enteweb.install_confirm', ['locale' => $locale]);
        } else {
            return redirect()->route('enteweb.dashboard')->with('warning', trans('enteweb.already_installed'));
        }
    }

    public function install($locale)
    {
        if (!Enteweb::checkInstalled()) {
            $exitCode = Artisan::call('migrate');

            $user = User::create([
              'name' => env('USER_NAME'),
              'username' => str_slug(env('USER_NAME')),
              'email' => env('USER_EMAIL'),
              'password' => bcrypt(env('USER_PASSWORD')),
              'created_by' => 'Installation account'
            ]);

            $permission = new Permission;

            $permission->name = env('ADMINISTRATOR_ROLE_NAME');
            $permission->type = 5;

            $user->permissions()->save($permission);

            if (Auth::attempt(['email' => env('USER_EMAIL'), 'password' => env('USER_PASSWORD')])) {
                // Authentication passed...

                $file_location = base_path() . '/.env';
                $default = "\nENTEWEB_INSTALLED=true";
                file_put_contents($file_location, $default, FILE_APPEND);

                $url = route('enteweb.dashboard');
                return redirect()->intended($url)->with('success', trans('enteweb.install_success'));
            } else {
                die("<b>ERRORE: </b> Qualcosa é andato storto, riprova.");
            }
        } else {
            return redirect()->route('enteweb.dashboard')->with('warning', trans('enteweb.already_installed'));
        }
    }
}
