<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request): Response
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (! Auth::attempt($credentials)) {
            return response(['message' => 'Invalid credentials'], 422);
        }

        $token = $request->user()->createToken('api')->plainTextToken;

        $isAdmin = DB::table('administradores')->where('idUsuario', $request->user()->idUsuario)->exists();

        return response([
            'token' => $token,
            'user'  => $request->user(),
            'isAdmin' => $isAdmin,
        ], 200);
    }

    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $request->user()->currentAccessToken()?->delete();
        return response()->noContent();
    }
}
