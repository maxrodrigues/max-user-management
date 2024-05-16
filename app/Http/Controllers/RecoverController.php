<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

class RecoverController extends Controller
{
    public function recover(Request $request): JsonResponse
    {
        $token = bcrypt(Carbon::now() . auth()->user()->email);
        DB::table('password_reset_tokens')
            ->insert([
                'email' => auth()->user()->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

        $resetPwd = DB::table('password_reset_tokens')
            ->where('email', auth()->user()->email)
            ->first();

        return new JsonResponse([
            'reset_token' => $resetPwd->token
        ], Response::HTTP_OK);
    }

    public function changePassword(Request $request): JsonResponse
    {
        
        return new JsonResponse([], Response::HTTP_OK);
    }
}
