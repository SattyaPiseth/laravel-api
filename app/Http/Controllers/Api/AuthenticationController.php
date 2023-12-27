<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthenticationController extends Controller
{
    /**
     * Registration Req
     */
    public function register(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:' . User::class,
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('Laravel10PassportAuth')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Login Req
     */
    public function login(Request $request): JsonResponse
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $user = auth()->user();
//            $token = $user->createToken('Laravel10PassportAuth',)->accessToken;
            $token = $user->createToken('My Token', ['edit'])->accessToken;
            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        } else
            return response()->json(['error' => 'Incorrect username, email or password'], 401);

    }

    public function profile(): JsonResponse
    {
        $user = auth()->user();
        return response()->json(['user' => $user]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();
        return response()->json();
    }
}
