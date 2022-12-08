<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\FormEventRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers, RedirectsUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';


    /** @var \App\Repositories\FormEventRepository */
    protected $formEventRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FormEventRepository $formEventRepository)
    {
        $this->middleware('guest')->except('logout');
        $this->formEventRepository = $formEventRepository;
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $this->formEventRepository->create(
                null,
                Auth::id(),
                'info',
                'Users session has started.'
            );

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        notify()->error('These credentials do not match our records.');

        return view('auth.login');
    }

    /**
     * Logout the user.
     */
    public function logout()
    {
        $this->formEventRepository->create(
            null,
            Auth::id(),
            'info',
            'Users session has ended.'
        );

        Auth::logout();

        // Clear cookie.
        setcookie('X-AES128', null, -1, '/');

        notify()->success('You have successfully logged out.');
        return redirect('login');
    }
}
