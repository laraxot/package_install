<!doctype html>

<html lang="it">
<head>
  <meta charset="utf-8">

  <title>{{ trans('enteweb.installer') }}</title>
  <meta name="description" content="Enteweb - Administration panel">
  <meta name="author" content="Q-Web Agency San Donà di Piave">

  <link rel="shortcut icon" href="{{ asset('/enteweb_public/favicon/enteweb.ico') }}"/>

  {!! Enteweb::includeAssets('enteweb_header') !!}

  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body style="background-color: #EEEEEE;">

  <div class="ui inverted dimmer"><div class="ui text loader">{{ trans('enteweb.installing') }}</div></div>

  <div style="padding-top: 100px;">
    <center>
      <img class="ui medium image" src="{{ Enteweb::entewebLogo() }}">
    </center>
  </div>
  <div style="padding-top: 100px;">
    <div id="welcome" style="display:none;">
      <center>
        <h1 class="ui huge header">{{ trans('enteweb.install_welcome') }}</h1>
      </center>
    </div>
    <div id="form" style="display:none;">
      <div class="ui doubling stackable one column grid container">
        <div class="column">
          <div class="ui very padded segment">

            <div class="ui equal width form">
              <form method="POST">
                {{ csrf_field() }}
                <h1 class="ui header">{{ trans('enteweb.install_app_info') }}</h1><br>
                <div class="fields">
                  <div class="required field">
                    <label>{{ trans('enteweb.install_app_name') }}</label>
                    <input name="APP_NAME" required type="text" value="Enteweb">
                  </div>
                </div>
                <br>

                <h1 class="ui header">{{ trans('enteweb.install_personal_info') }}</h1><br>
                <div class="fields">
                  <div class="required field">
                    <label>{{ trans('enteweb.install_your_name') }}</label>
                    <input name="USER_NAME" required type="text" placeholder="{{ trans('enteweb.install_your_name') }}">
                  </div>
                  <div class="required field">
                    <label>{{ trans('enteweb.install_your_email') }}</label>
                    <input name="USER_EMAIL" required type="email" placeholder="{{ trans('enteweb.install_your_email') }}">
                  </div>
                </div>
                <div class="fields">
                  <div class="required field">
                    <label>{{ trans('enteweb.install_your_password') }}</label>
                    <input name="USER_PASSWORD" required type="password" placeholder="{{ trans('enteweb.install_your_password') }}">
                  </div>
                  <div class="required field">
                    <label>{{ trans('enteweb.install_your_password_r') }}</label>
                    <input name="USER_PASSWORD_confirmation" required type="password" placeholder="{{ trans('enteweb.install_your_password_r') }}">
                  </div>
                </div>

                <br>

                <h1 class="ui header">{{ trans('enteweb.install_roles_info') }}</h1><br>
                <div class="fields">
                  <div class="required field">
                    <label>{{ trans('enteweb.install_default_admin_role_name') }}</label>
                    <input name="ADMINISTRATOR_ROLE_NAME" required type="text" placeholder="{{ trans('enteweb.install_default_admin_role_name') }}">
                  </div>
                  <div class="required field">
                    <label>{{ trans('enteweb.install_default_role_name') }}</label>
                    <input name="DEFAULT_ROLE_NAME" required type="text" placeholder="{{ trans('enteweb.install_default_role_name') }}">
                  </div>
                </div>

                <br>

                <h1 class="ui header">{{ trans('enteweb.install_database_info') }} (MySQL)</h1><br>
                <div class="fields">
                  <div class="required field">
                    <label>{{ trans('enteweb.install_database_host') }}</label>
                    <input name="DB_HOST" required value="localhost" type="text" placeholder="{{ trans('enteweb.install_database_host') }}">
                  </div>
                  <div class="required field">
                    <label>{{ trans('enteweb.install_database_port') }}</label>
                    <input name="DB_PORT" required value="3306" type="number" placeholder="{{ trans('enteweb.install_database_port') }}">
                  </div>
                </div>
                <div class="fields">
                  <div class="required field">
                    <label>{{ trans('enteweb.install_database_name') }}</label>
                    <input name="DB_DATABASE" required type="text" placeholder="{{ trans('enteweb.install_database_name') }}">
                  </div>
                  <div class="required field">
                    <label>{{ trans('enteweb.install_database_username') }}</label>
                    <input name="DB_USERNAME" required type="text" placeholder="{{ trans('enteweb.install_database_username') }}">
                  </div>
                  <div class="field">
                    <label>{{ trans('enteweb.install_database_password') }}</label>
                    <input name="DB_PASSWORD" type="password" placeholder="{{ trans('enteweb.install_database_password') }}">
                  </div>
                </div>

                <br>

                <h1 class="ui header">{{ trans('enteweb.install_mail_info') }}</h1><br>
                <div class="fields">
                  <div class="field">
                    <label>{{ trans('enteweb.install_mail_driver') }}</label>
                    <div class="ui fluid search selection dropdown">
                      <input type="hidden" name="MAIL_DRIVER">
                      <i class="dropdown icon"></i>
                      <div class="default text">{{ trans('enteweb.install_mail_driver') }}</div>
                      <div class="menu">
                        <div class="item" data-value="smtp">SMTP</div>
                        <div class="item" data-value="mail">Mail</div>
                        <div class="item" data-value="sendmail">Sendmail</div>
                        <div class="item" data-value="mailgun">Mailgun</div>
                        <div class="item" data-value="mandrill">Mandrill</div>
                        <div class="item" data-value="ses">SES</div>
                        <div class="item" data-value="sparkpost">Sparkpost</div>
                        <div class="item" data-value="log">Log</div>
                      </div>
                    </div>
                  </div>
                  <div class="field">
                    <label>{{ trans('enteweb.install_mail_host') }}</label>
                    <input name="MAIL_HOST" type="text" placeholder="{{ trans('enteweb.install_mail_host') }}">
                  </div>
                  <div class="field">
                    <label>{{ trans('enteweb.install_mail_port') }}</label>
                    <input name="MAIL_PORT" type="number" placeholder="{{ trans('enteweb.install_mail_port') }}">
                  </div>
                </div>
                <div class="fields">
                  <div class="field">
                    <label>{{ trans('enteweb.install_mail_username') }}</label>
                    <input name="MAIL_USERNAME" type="text" placeholder="{{ trans('enteweb.install_mail_username') }}">
                  </div>
                  <div class="field">
                    <label>{{ trans('enteweb.install_mail_password') }}</label>
                    <input name="MAIL_PASSWORD" type="password" placeholder="{{ trans('enteweb.install_mail_password') }}">
                  </div>
                </div>
                <div class="fields">
                  <div class="field">
                    <label>{{ trans('enteweb.install_mail_encryption') }}</label>
                    <input name="MAIL_ENCRYPTION" type="text" placeholder="{{ trans('enteweb.install_mail_encryption') }}">
                  </div>
                  <div class="field">
                    <label>{{ trans('enteweb.install_mail_from') }}</label>
                    <input name="MAIL_FROM" type="email" placeholder="{{ trans('enteweb.install_mail_from') }}">
                  </div>
                  <div class="field">
                    <label>{{ trans('enteweb.install_mail_name') }}</label>
                    <input name="MAIL_NAME" type="text" placeholder="{{ trans('enteweb.install_mail_name') }}">
                  </div>
                </div>

                <br><br>

                <center>
                  <button class="ui huge positive button" type="submit">{{ trans('enteweb.install_enteweb') }}</button>
                </center>
                <br>
              </form>
            </div>

          </div>
          <br>
        </div>
      </div>
    </div>
  </div>


  {!! Enteweb::includeAssets('enteweb_bottom') !!}

  <script>
  $('#welcome').fadeIn(1000, function(){
    $(this).fadeOut(1000, function(){
      $('#form').fadeIn(1000);
    });
  });
  </script>

</body>
</html>
