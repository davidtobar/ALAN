<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginPrincipal()
    {
        $login = DB::table('login')->first();

        return view('index', compact('login'));
    }


    public function log(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // if ($this->attemptLogin($request)) {
        //     return $this->sendLoginResponse($request);
        // }

        // This section is the only change
           if ($this->guard()->validate($this->credentials($request))) {
               $user = $this->guard()->getLastAttempted();

               // Make sure the user is active
               if ($user->status == 1 && $this->attemptLogin($request)) {
                   // Send the normal successful login response
                   return $this->sendLoginResponse($request);
               } else {
                   // Increment the failed login attempts and redirect back to the
                   // login form with an error message.
                   $this->incrementLoginAttempts($request);
                   return $this->sendFailedLoginStatus($request);
               }
           }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendFailedLoginStatus(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => 'You must be active to login.',
        ]);
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (\Auth::check() && \Auth::user()->role == '2') {
            return '/admin';
        }

        elseif (\Auth::check() && \Auth::user()->role == '1') {
            return '/management';
        }

        else {
            return '/user';
        }
    }

    /**
     * Get the needed authorization credentials from the request.
     */

    protected function credentials(Request $request)
    {
        $field = filter_var($request->get($this->username()), FILTER_VALIDATE_EMAIL)
            ? $this->username()
            : 'username';

        return [
            $field => $request->get($this->username()),
            'password' => $request->password
        ];
    }

}
