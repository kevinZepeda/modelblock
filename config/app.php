<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Application Debug Mode
	|--------------------------------------------------------------------------
	|
	| When your application is in debug mode, detailed error messages with
	| stack traces will be shown on every error that occurs within your
	| application. If disabled, a simple generic error page is shown.
	|
	*/

	'debug' => env('APP_DEBUG', false),

	/*
	|--------------------------------------------------------------------------
	| Application Default Name
	|--------------------------------------------------------------------------
	|
	| When your application is in single_mode, the registartion will be 
	| disallowed and the purpose of using the applicaiton is concetrating only
	| on one Account for the whole system, eg. your company or team only...
	|
	*/

	'name' => '<b>Agile</b>Team',

	/*
	|--------------------------------------------------------------------------
	| Application mass usaged definition
	|--------------------------------------------------------------------------
	|
	| When your application is in single_mode, the registartion will be 
	| disallowed and the purpose of using the applicaiton is concetrating only
	| on one Account for the whole system, eg. your company or team only...
	|
	*/

	'single' => true,

	/*
	|--------------------------------------------------------------------------
	| Application URL
	|--------------------------------------------------------------------------
	|
	| This URL is used by the console to properly generate URLs when using
	| the Artisan command line tool. You should set this to the root of
	| your application so that it is used when running Artisan tasks.
	|
	*/

	'url' => 'http://localhost',

	/*
	|--------------------------------------------------------------------------
	| Application Timezone
	|--------------------------------------------------------------------------
	|
	| Here you may specify the default timezone for your application, which
	| will be used by the PHP date and date-time functions. We have gone
	| ahead and set this to a sensible default for you out of the box.
	|
	*/

	'timezone' => 'UTC',

	/*
	|--------------------------------------------------------------------------
	| Application Locale Configuration
	|--------------------------------------------------------------------------
	|
	| The application locale determines the default locale that will be used
	| by the translation service provider. You are free to set this value
	| to any of the locales which will be supported by the application.
	|
	*/

	'locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Application Fallback Locale
	|--------------------------------------------------------------------------
	|
	| The fallback locale determines the locale to use when the current one
	| is not available. You may change the value to correspond to any of
	| the language folders that are provided through your application.
	|
	*/

	'fallback_locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Encryption Key
	|--------------------------------------------------------------------------
	|
	| This key is used by the Illuminate encrypter service and should be set
	| to a random, 32 character string, otherwise these encrypted strings
	| will not be safe. Please do this before deploying an application!
	|
	*/

	'key' => env('APP_KEY', 'SomeRandomString'),

	'cipher' => MCRYPT_RIJNDAEL_128,

	/*
	|--------------------------------------------------------------------------
	| Logging Configuration
	|--------------------------------------------------------------------------
	|
	| Here you may configure the log settings for your application. Out of
	| the box, Laravel uses the Monolog PHP logging library. This gives
	| you a variety of powerful log handlers / formatters to utilize.
	|
	| Available Settings: "single", "daily", "syslog", "errorlog"
	|
	*/

	'log' => 'daily',

	/*
	|--------------------------------------------------------------------------
	| Autoloaded Service Providers
	|--------------------------------------------------------------------------
	|
	| The service providers listed here will be automatically loaded on the
	| request to your application. Feel free to add your own services to
	| this array to grant expanded functionality to your applications.
	|
	*/

	'providers' => [

		/*
		 * Laravel Framework Service Providers...
		 */
		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		'Illuminate\Auth\AuthServiceProvider',
		'Illuminate\Bus\BusServiceProvider',
		'Illuminate\Cache\CacheServiceProvider',
		'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
		'Illuminate\Routing\ControllerServiceProvider',
		'Illuminate\Cookie\CookieServiceProvider',
		'Illuminate\Database\DatabaseServiceProvider',
		'Illuminate\Encryption\EncryptionServiceProvider',
		'Illuminate\Filesystem\FilesystemServiceProvider',
		'Illuminate\Foundation\Providers\FoundationServiceProvider',
		'Illuminate\Hashing\HashServiceProvider',
		'Illuminate\Mail\MailServiceProvider',
		'Illuminate\Pagination\PaginationServiceProvider',
		'Illuminate\Pipeline\PipelineServiceProvider',
		'Illuminate\Queue\QueueServiceProvider',
		'Illuminate\Redis\RedisServiceProvider',
		'Illuminate\Auth\Passwords\PasswordResetServiceProvider',
		'Illuminate\Session\SessionServiceProvider',
		'Illuminate\Translation\TranslationServiceProvider',
		'Illuminate\Validation\ValidationServiceProvider',
		'Illuminate\View\ViewServiceProvider',

		/*
		 * Application Service Providers...
		 */
		'App\Providers\AppServiceProvider',
		'App\Providers\BusServiceProvider',
		'App\Providers\ConfigServiceProvider',
		'App\Providers\EventServiceProvider',
		'App\Providers\RouteServiceProvider',

		/*
		 * Backup Provider...
		 */
		'Spatie\Backup\BackupServiceProvider',
	],

	/*
	|--------------------------------------------------------------------------
	| Class Aliases
	|--------------------------------------------------------------------------
	|
	| This array of class aliases will be registered when this application
	| is started. However, feel free to register as many as you wish as
	| the aliases are "lazy" loaded so they don't hinder performance.
	|
	*/

	'aliases' => [

		'App'       => 'Illuminate\Support\Facades\App',
		'Artisan'   => 'Illuminate\Support\Facades\Artisan',
		'Auth'      => 'Illuminate\Support\Facades\Auth',
		'Blade'     => 'Illuminate\Support\Facades\Blade',
		'Bus'       => 'Illuminate\Support\Facades\Bus',
		'Cache'     => 'Illuminate\Support\Facades\Cache',
		'Config'    => 'Illuminate\Support\Facades\Config',
		'Cookie'    => 'Illuminate\Support\Facades\Cookie',
		'Crypt'     => 'Illuminate\Support\Facades\Crypt',
		'DB'        => 'Illuminate\Support\Facades\DB',
		'Eloquent'  => 'Illuminate\Database\Eloquent\Model',
		'Event'     => 'Illuminate\Support\Facades\Event',
		'File'      => 'Illuminate\Support\Facades\File',
		'Hash'      => 'Illuminate\Support\Facades\Hash',
		'Input'     => 'Illuminate\Support\Facades\Input',
		'Inspiring' => 'Illuminate\Foundation\Inspiring',
		'Lang'      => 'Illuminate\Support\Facades\Lang',
		'Log'       => 'Illuminate\Support\Facades\Log',
		'Mail'      => 'Illuminate\Support\Facades\Mail',
		'Password'  => 'Illuminate\Support\Facades\Password',
		'Queue'     => 'Illuminate\Support\Facades\Queue',
		'Redirect'  => 'Illuminate\Support\Facades\Redirect',
		'Redis'     => 'Illuminate\Support\Facades\Redis',
		'Request'   => 'Illuminate\Support\Facades\Request',
		'Response'  => 'Illuminate\Support\Facades\Response',
		'Route'     => 'Illuminate\Support\Facades\Route',
		'Schema'    => 'Illuminate\Support\Facades\Schema',
		'Session'   => 'Illuminate\Support\Facades\Session',
		'Storage'   => 'Illuminate\Support\Facades\Storage',
		'URL'       => 'Illuminate\Support\Facades\URL',
		'Validator' => 'Illuminate\Support\Facades\Validator',
		'View'      => 'Illuminate\Support\Facades\View',

	],

	/*
	|--------------------------------------------------------------------------
	| Beatmode 
	|--------------------------------------------------------------------------
	|
	| You can switch this parameter if you would like to indicate that the system
	| is in a beta phase...
	|
	*/

	'beta' => true,
	'betaname' => 'Easy Project Management Tool',

	/*
	|--------------------------------------------------------------------------
	| Warning Message 
	|--------------------------------------------------------------------------
	|
	| You can switch this parameter if you would like to indicate that the system
	| is in a beta phase...
	|
	*/

	'warning' => [
		'enabled' => false,
		'message' => 'The Responsive Version is currently under development. Thank you for your patience.'
	],

	/*
	|--------------------------------------------------------------------------
	| Thank you Message 
	|--------------------------------------------------------------------------
	|
	| You can switch this parameter if you would like to indicate that the system
	| is in a beta phase...
	|
	*/

	'thankyou' => [
		'enabled' => false,
		'message' => 'Thank you for using our service. We are running a soft release to ensure stability, <strong>in 10 days</strong> we will push a final relase for three major features: <strong>time &amp; expenses tracking and scope questionnaires</strong>.'
	],

	/*
	|--------------------------------------------------------------------------
	| Thank you Message 
	|--------------------------------------------------------------------------
	|
	| You can switch this parameter if you would like to indicate that the system
	| is in a beta phase...
	|
	*/

	'copyright' => [
		'html' => '<strong>Copyright &copy; 2015 <a href="https://codecanyon.net/user/kuveljicstudio" target="_blank">KUVELJIC STUDIO SOLUTIONS</a></strong> All rights reserved.',
		'terms' => ''
	],

	/*
	|--------------------------------------------------------------------------
	| Active System Features
	|--------------------------------------------------------------------------
	|
	| You can switch on and off features of the system by setting up
	| the parameters to true or false...
	|
	*/

	'features' => [
		'timesheets' => true,
		'boards'	=> true,
		'projects' => true,
		'finances' => true,
		'customers' => true,
		'reports' => true,
		'multilanguage' => false,
		'fileupload' => true
 	],

	/*
	|--------------------------------------------------------------------------
	| Active System Features
	|--------------------------------------------------------------------------
	|
	| You can switch on and off features of the system by setting up
	| the parameters to true or false...
	|
	*/

	'landing_page' => 'board', //eg. 'board, 'timesheet'

	/*
	|--------------------------------------------------------------------------
	| Security Salt 
	|--------------------------------------------------------------------------
	|
	*/
	'salt' => [
		'qa' => '5458dc8139129186a99cad90ece966ea'
	],

	/*
	|--------------------------------------------------------------------------
	| Google Analytics Tracking Code
	|--------------------------------------------------------------------------
	|
	*/

	'ga_tracking_code' => NULL,

	/*
	|--------------------------------------------------------------------------
	| Google reCaptcha Configuration
	|--------------------------------------------------------------------------
	*/
	'reCaptcha' => [
		'enabled' => false,
		'sitekey' => '6LflQwcTAAAAAIs3l03d2qF7UY05mw0T34qT2T5u',
		'secret'  => '6LflQwcTAAAAAMyVkK-nvcnW1s4th3zgLcqeHHgb'		
	],

];
