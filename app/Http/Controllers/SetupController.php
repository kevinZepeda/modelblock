<?php namespace App\Http\Controllers;

use App\Models\ktBoard;
use App\Models\ktCustomer;
use Doctrine\DBAL\Driver\Mysqli\MysqliException;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Http\Request;
use Auth;
use App\Models\ktUser;
use App\Models\User;
use App\Models\ktProject;
use App\Models\ktSettings;
use League\Flysystem\Exception;
use Route;
use App\Models\ktFinance;
use RecursiveIteratorIterator;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
Use DB;
use Illuminate\Support\Facades\File;

class SetupController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function install(Request $request)
    {

        $installed = false;
        if (file_exists('../.env')) {
            if (!empty(DB::connection()->getDatabaseName())) {
                return redirect('/');
            }
        }

        $error = [];
        $file_premissions_issues = [];

        $path = realpath('../storage');

        if (isset($_POST['event']) && $_POST['event'] == 'priv') {

            $premissions = decoct(fileperms($path) & 0777);
            if ((fileperms($path) & 0777) !== 0777) {
                chmod($path, 0777);
            }

            $iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, 0));
            foreach ($iter as $file) {
                if ($file->getFilename() == '.') {
                    chmod($file->getPath(), 0777);
                }
            }

        }
        $premissions_issue = false;
        if (isset($_POST['event']) && $_POST['event'] == 'install' && $installed == false) {
            try {
                if (strpos($_POST['dbhost'], ':') !== false) {
                    list($host, $port_number) = explode(':', trim($_POST['dbhost']), 2);
                    $port = ';port=' . $port_number;
                } else {
                    $host = trim($_POST['dbhost']);
                    $port = '';
                }
                $dbconfig = "APP_ENV=production\nAPP_DEBUG=false\nDB_HOST="
                    . $host
                    . "\nDB_DATABASE="
                    . $_POST['dbname']
                    . "\nDB_USERNAME="
                    . $_POST['dbuser']
                    . "\nDB_PASSWORD="
                    . $_POST['dbpassword'];

                if (!empty($port)) {
                    $dbconfig = $dbconfig . "\nDB_PORT=" . $port_number;
                }

                try {
                    $created = File::put('../.env', $dbconfig);

                    Artisan::call('config:clear');
                    Artisan::call('config:cache');

                    DB::reconnect();
                    if ($created !== false && !empty(DB::connection()->getDatabaseName())) {
                        Artisan::call('migrate', ['--force' => 'true']);

                        $password = Hash::make($_POST['password']);
                        $date = date("Y-m-d H:i:s");

                        $accountId = DB::table('account')->insertGetId([
                            'company_name' => ''
                        ]);

                        $userId = DB::table('users')->insertGetId([
                            'email' => $_POST['email_address'],
                            'password' => $password,
                            'created_at' => $date,
                            'updated_at' => $date
                        ]);

                        DB::table('users_extended')->insert([
                            'user_id' => $userId,
                            'account_id' => $accountId,
                            'first_name' => '',
                            'last_name' => '',
                            'user_level' => 'ADMIN'
                        ]);

                    }
                }catch (\PDOException $l){
                    unlink('../.env');
                    $error[] = "Database Issue: " . $l->getMessage();
                }catch (Exception $e) {
                    unlink('../.env');
                    $premissions_issue = nl2br($dbconfig);
                    $error[] = 'Env file issue: ' . $e->getMessage();
                }
            } catch (Exception $e) {
                $error[] = $e->getMessage();
            }
        }

        $premissions = decoct(fileperms($path) & 0777);
        if ((fileperms($path) & 0777) !== 0777) {
            $file_premissions_issues[] = [
                'name' => $path,
                'premissions' => $premissions
            ];
        }

        $iter = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($iter as $file) {
            if ($file->getFilename() == '.') {
                $premissions = decoct(fileperms($file->getPath()) & 0777);
                if ((fileperms($file->getPath()) & 0777) !== 0777) {
                    $file_premissions_issues[] = [
                        'name' => $file->getPath(),
                        'premissions' => $premissions
                    ];
                }
            }
        }

        $installed = false;
        if (file_exists('../.env')) {
            if (!empty(DB::connection()->getDatabaseName())) {
                $installed = true;
            }
        }


        return view('setup.install', [
            'file_premissions_issues' => $file_premissions_issues,
            'installed' => $installed,
            'premissions' => $premissions,
            'error' => $error,
            'premissions_issue' => $premissions_issue
        ]);
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function update(Request $request)
    {

        $installed = false;
        if (file_exists('../.env')) {
            if (!empty(DB::connection()->getDatabaseName())) {
                return redirect('/');
            }
        }
        return [];
    }

}
