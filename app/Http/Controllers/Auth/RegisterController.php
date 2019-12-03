<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use App\User;
use App\UsersRoles;
use App\Role;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function showRegistrationForm()
    {
        if(auth()->check() && (auth()->user()->hasRole('admin'))){
            $roles = Role::all();
            return view('auth.register', compact('roles'));
        }else{
            return redirect()->route('home');
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        Log::info('data', [$data]);
        if(auth()->check() && (auth()->user()->hasRole('admin'))){
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            Log::info('user', [$user]);
            //cadastrar users_roles
            UsersRoles::create([
                'user_id' => $user->id,
                'role_id' => $data['role_id']
            ]);
            return $user;
        }else{
            return redirect()->route('home');
        }
    }


    public function register(Request $request)
    {
        Log::info('userlogado', [auth()->user()]);
        if(auth()->check() && (auth()->user()->hasRole('admin'))){
            $this->validator($request->all())->validate();

            event(new Registered($user = $this->create($request->all())));

            return $this->registered($request, $user)
                ?: redirect($this->redirectPath());
        }else{
            return redirect()->route('home');
        }
    }
}
