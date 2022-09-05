<?php

namespace App\Http\Controllers;

use App\Models\AnimalData;
use App\Models\FavoriteCat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class userControllers extends Controller
{
    public function singup(Request $request)
    {
        $validateData =
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        User::create([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => Hash::make($validateData['password']),
            'isAdmin' => false,
        ]);
    }
    public function login(Request $request)
    {
        $validateData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt(['email' => $validateData['email'], 'password' => $validateData['password']])) {
            return ['icon' => 'warning', 'message' => '帳號或密碼錯誤'];
        } else {
            $user = User::where('email', $validateData['email'])->first();
            $token = $user->createToken('token');

            Auth::loginUsingId($user['id'], $remember = false);

            return response(['icon' => 'success', 'message' => '登陸成功', 'userData' => ['user' => $user,
                'favoriteCats' => $user->cat()->get(),
                'isAuthenticated' => Auth::check()],
                'token' => $token->plainTextToken,
            ]);
        }
    }
    public function faceBookLogin()
    {
        return Socialite::driver('facebook')->redirect();
    }
    public function faceBookLoginCallBack()
    {
        $user = Socialite::driver('facebook')->user();

        return response(dump($user));
    }
    public function logout()
    {
        $user = Auth::user();
        $tokenId = $user['id'];

        $user->tokens()->where('tokenable_id', $tokenId)->delete();

        Auth::guard('web')->logout();
    }
    public function getCurrentUser()
    {
        $user = Auth::user();

        return ['user' => $user, 'isAuthenticated' => Auth::check(),
            'favoriteCats' => User::find($user['id'])->cat()->get(),
        ];
    }

    public function addFavorite($id)
    {
        $user = Auth::user();

        $lastData = AnimalData::all()->last();

        if ($id > $lastData['id'] || $id <= -1) {
            return response()->json([
                'icon' => 'error',
                'message' => '找不到編號，請重新操作',
            ]);
        } else {
            FavoriteCat::create([
                'user_id' => $user['id'],
                'animal_data_id' => $id,
            ]);
            return response()->json([
                'icon' => 'success',
                'message' => '已加入最愛',
            ]);

        }
    }
    public function removeFavorite($id)
    {
        $user = Auth::user();

        $cat = FavoriteCat::where('user_id', $user['id'])->get()->firstWhere('animal_data_id', $id);

        if ($cat) {
            return response()->json([
                'icon' => 'success',
                'message' => '成功刪除',
            ]);

        } else {
            return response()->json([
                'icon' => 'error',
                'message' => '刪除失敗，請重新操作',
            ]);
        }
    }
}
