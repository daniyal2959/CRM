<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login()
    {
//        return Hash::make('12345678');
        return response(view('Auth.login'));
    }

    public function check(Request $request)
    {
        $loginDatas = $request->only('email', 'password');
        if( Auth::attempt($loginDatas) ){

            return redirect()->route('dashboard');
        }

        return redirect()->route('login');
    }

    public function register()
    {
        return response(view('Auth.register'));
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|min:8',
            'privacy' => 'accepted'
        ]);

        $user = new User();
        $userType = UserType::find(1);

        $founded = $user->where('email', $request->email);
        if ($founded->count() == 0) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->UTID = $userType->id;
            $user->save();

            return redirect()->route('login');
        }
        else{
            return redirect()->route('register');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('login');
    }
}
