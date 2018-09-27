<?php namespace App;

use App\Models\ktLang;
use App\Models\ktUser;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use DB;
use Auth;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

    public function isAdmin(){
        $is_admin = DB::table('users_extended')
            ->where('user_id', '=', $this->id)
            ->where('user_level', '=', 'ADMIN')
            ->get();
        return $is_admin ? true : false;
    }

    public function isStaff(){
        $is_admin = DB::table('users_extended')
            ->where('user_id', '=', $this->id)
            ->where('user_level', '=', 'USER')
            ->get();
        return $is_admin ? true : false;
    }

    public function canManage(){
        $is_admin = DB::table('users_extended')
            ->where('user_id', '=', $this->id)
            ->where('user_level', '=', 'MANAGER')
            ->get();
        return $is_admin ? true : false;
    }

    public function isActive(){
        $user = DB::table('users_extended')
            ->select('active')
            ->where('user_id', '=', $this->id)
            ->first();
        return $user->active ? true : false;
    }

    public function isClient(){
        $is_admin = DB::table('users_extended')
            ->where('user_id', '=', $this->id)
            ->where('user_level', '=', 'CLIENT')
            ->get();
        return $is_admin ? true : false;
    }

    public function getFullName(){
    	$user = DB::table('users_extended')
            ->where('user_id', '=', $this->id)
            ->first();
        return $user->first_name . ' ' . $user->last_name;
    }

    public function getSystemColors(){
        if(Auth::check()) {
            $account = DB::table('account')
                ->where('id', '=', ktUser::getAccountId())
                ->first();
            return [
                'layout_color' => $account->system_layout_color,
                'text_color' => $account->system_layout_text_color,
            ];
        }
        return false;
    }

    public function getSystemLogo(){
        $account = DB::table('account')
            ->where('id', '=', ktUser::getAccountId())
            ->first();
        return ($account->system_logo != 'NULL') ? $account->system_logo : false;
    }

   public function getName(){
        $user = DB::table('users_extended')
            ->where('user_id', '=', $this->id)
            ->first();
        return $user->first_name;
    }

    public function getEmail(){
        $user = DB::table('users')
            ->where('id', '=', $this->id)
            ->first();
        return $user->email;
    }

    public function getLanguage(){
        $user = DB::table('users_extended')
            ->where('user_id', '=', $this->id)
            ->first();
        return ktLang::$invoiceLangMap[$user->language];
    }
}

function adjustBrightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
        $color   = hexdec($color); // Convert to decimal
        $color   = max(0,min(255,$color + $steps)); // Adjust color
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }

    return $return;
}