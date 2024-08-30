<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(){
        return view('front.account.login');
    }

    public function register(){
        return view('front.account.register');
    }

    public function registerProcess(Request $request){
        // dd($request->all()); 
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|min:10|max:15',
            'password' => 'required|min:6|confirmed'
        ]);

        if($validator->passes()){

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone;
            $user->role = 1;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'User created'
            ]);
            

        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }

    public function authentication(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->passes()){
            
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))){
                // return view
                return redirect()->route('account.profile');
            } else {
                return redirect()
                    ->route('front.login')
                    ->withInput($request->only('email'))
                    ->with('error', 'Either email/password is in-valid');
            }

        } else {
            return redirect()->route('front.login')
                            ->withErrors($validator)
                            ->withInput($request->only('email'));
        }
    }

    public function profile(){
        return view('front.account.profile');
    }

    public function logout(){
        Auth()->logout();
        return redirect()->route('front.login')->with('success','logout successfully');
    }
}
