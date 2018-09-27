<?php namespace App\Services;

use App\User;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;
use DB;
use Mail;
use App\Models\ktSettings;
use Config;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{

		$captchaRule = [];
		$captchaRuleMessages = [];		
		if(Config::get('app.reCaptcha.enabled')){
			$data['re-captcha'] = ktSettings::getCaptcha($data['g-recaptcha-response']);
			$captchaRule = [
				're-captcha' => 'required|accepted'
			];
			$captchaRuleMessages = [

			];
		}
		return Validator::make($data, array_merge([
				'name' => 'required|max:255',
				'email' => 'required|email|max:255|unique:users',
				'password' => 'required|confirmed|min:6',
			], $captchaRule),$captchaRuleMessages
		);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{

		$user = User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
		]);

		DB::table('account')->insert([
			'company_name' => $data['company_name']
        ]);

	 	$account_id = DB::getPdo()->lastInsertId();

	 	DB::table('users_extended')->insert([
            'user_id'       => $user->id,
            'account_id'    => $account_id,
            'first_name'    => $data['name'],
            'user_level'    => 'ADMIN'
        ]);

		Mail::send('emails.welcome', $data, function($message) use ($data)
		{
		    $message->to($data['email'], $data['name'])->subject('Welcome to AgileTeam!');
		});

		return $user;
	}

}
