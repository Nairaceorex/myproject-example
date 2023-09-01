<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function login(Request $request){
        

        $incomingFields = $request->validate([
            'loginname'=>'required',
            'loginpassword'=>'required'
        ]);
        /*if (auth()->check()) {
            return redirect('/');
        }*/
        if (auth()->attempt(['name'=>$incomingFields['loginname'], 'password'=>$incomingFields['loginpassword']])){
            $request->session()->regenerate();
            /*$user = User::find(Auth::id());
            $token = $user->tokens()->first();*/
            
        }
        

        $token = DB::table('personal_access_tokens')
            ->where('tokenable_id', Auth::id())
            ->value('token');
        return view('home', ['token' => $token]);
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
        $user->createToken('API Token');
        $token = DB::table('personal_access_tokens')
            ->where('tokenable_id', Auth::id())
            ->value('token');
        Auth::login($user);
        return view('home', ['token' => $token]);
        
    }   

    public function updateToken(Request $request)
{
    $user = User::find(Auth::id());
    #$accessToken = $user->createToken('API Token');
    $token = Str::random(64);

    // Update the token value in the personal_access_tokens table
    DB::table('personal_access_tokens')
        ->where('tokenable_id', $user->id)
        ->update(['token' => $token]);

    return view('home', ['token' => $token]);
}
}