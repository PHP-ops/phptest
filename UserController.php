<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Auth $auth)
    {   
        $info = ['userName'=>$auth::user()?->name ?? null,'userRole' => $auth::user()?->roles()?->select('role_id')->get()[0]['role_id'] ?? null,'userBalance'=>$auth::user()->balance ?? 0,'userId' => $auth::user()->id ?? null];
        return view('main.welcome',$info);
    }
    public function login() {
        if (!(Auth::check()))
        {
        return view('reg.login');
        } else {
            abort(404);
        }
    }
    public function signup() {
        if (!(Auth::check()))
        {
        return view('reg.signup');
        } else {
            abort(404);
        }
    }
    public function store(Request $request,Auth $auth,User $user) {
        if (Auth::check())
        {
            abort(404); // wtf this idinahui blyat incearca sa isi faca cont cand are deja
        }
        $input =  Validator::make($request->all(),[
            'login.username' => ['nullable','required'],
            'login.password' => ['nullable','required',Password::min(4)->letters()->numbers()->uncompromised()],
            'login.code' => ['nullable']
        ]);
        $errors = $input->errors();
        if($errors->all()) {
            Log::critical('somewthing went wrong with signup system',['errors'=>$errors,'submit form:' => $request->all() ]);
            return view('reg.signup',['input' => $input,'errors'=>$errors]);
        } else {
            
            $username = $input->safe()['login.username'];
            $password = Hash::make($input->safe()['login.password']);
            DB::insert("insert into users(name ,password) values (?, ?)", [$username,$password]);
            Auth::attempt(['name'=> $username,'password' => $input->safe()['login.password']],false);
            sleep(1);
            DB::insert("insert into role_user(role_id,user_id) values (1,:id)", ['id'=>$auth::id()]);
            
        }
        return redirect('/registration');

    }
    public function verify(Request $request,Auth $auth) {
        $validated = Validator::make($request->all(),
        [
            'login.username' => ['required'],
            'login.password' => ['required']
        ]);
        $credentials = [
            'name' => $validated->safe()['login.username'],
            'password' => $validated->safe()['login.password']
        ];
        if (Auth::attempt($credentials,true)) {
            $request->session()->regenerate();
        return redirect()->to('/');
        };
        return back()->withErrors([
            'errors' => 'something went wrong'
        ]);
    }
}
