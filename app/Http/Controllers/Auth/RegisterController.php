<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Notifications\FormsyEmail;
use App\Providers\RouteServiceProvider;
use App\Repositories\AppSettingsRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /** @var \App\Repositories\AppSettingsRepository */
    protected $appSettings;

    /** @var \App\Repositories\UseRepository */
    protected $userRepository;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AppSettingsRepository $appSetttingsRepository, UserRepository $userRepository)
    {
        $this->middleware('guest');
        $this->appSettings = $appSetttingsRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'country' => ['required', 'string', 'max:3'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     *
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'country' => $data['country'],
        ]);

        // Generate app id.
        $this->appSettings->setKey($user->id, Str::random(64));

        // Send welcome email.
        if (env("MAIL_HOST")) {
            $user->notify(new FormsyEmail("welcome", []));
        }

        return $user;
    }
}
