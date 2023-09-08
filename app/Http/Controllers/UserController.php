<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    public function login(Request $request){

        $incomingFields = $request->validate([
            'loginemail'=>'required',
            'loginpassword'=>'required'
        ]);
        
        if (auth()->attempt(['email'=>$incomingFields['loginemail'], 'password'=>$incomingFields['loginpassword']])){
            $request->session()->regenerate();
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            return view('home', ['token' => $success['token']]);
        }
        return response()->json(['error'=>'Unauthorised'], 401);
    }
    public function logout(){
        auth()->logout();
        return redirect('/');
    }
    public function register(Request $request){
        $incomingFields = $request->validate([
            'name'=>['required', 'min:3', 'max:10', Rule::unique('users', 'name')],
            'email'=>['required', 'email', Rule::unique('users', 'email')],
            'password'=>['required', 'min:8', 'max:200']
        ]);
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user = User::create($incomingFields);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
        Auth::login($user);
        return view('home', ['token' => $success['token']]);
    }   
    
    public function refresh(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = Auth::user(); 
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        return view('home',['token' => $success['token']]);
    }
    
}