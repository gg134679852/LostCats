<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class userControllers extends Controller
{
    public function singup(Request $request)
    {
        $validateData = 
        $request->validate([
           'name' =>'required|string',
           'email' =>'required|string|email|unique:users',
           'password' =>'required|string|confirmed'
        ]);

        User::create([
            'name'=>$validateData['name'],
            'email'=>$validateData['email'],
            'password'=>Hash::make($validateData['password'])
        ]);
    }
    public function login(Request $request)
   {
   $validateData = $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);
     
        if(!Auth::attempt(['email' => $validateData['email'], 'password' => $validateData['password']])){
        return ['icon'=>'warning','message'=>'帳號或密碼錯誤'];
    }else{
        $user = User::where('email',$validateData['email'])->first();
        $token = $user->createToken('token');
        
        Auth::loginUsingId($user['id'], $remember = false);

        return response(['icon'=>'success','message'=>'登陸成功','userData' => ['user'=>$user,'isAuthenticated'=> Auth::check()],
        'token'=>$token->plainTextToken
    ]);
  }
 }
 public function logout (Request $request)
 {
      $user = Auth::user();
      $tokenId = $user['id'];

      $user->tokens()->where('tokenable_id',$tokenId)->delete();

      Auth::guard('web')->logout();
 }
 public function getCurrentUser()
 {
    return ['user'=>Auth::user(),'isAuthenticated'=> Auth::check()];
 }
}
