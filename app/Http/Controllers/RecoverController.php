<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

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

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $token = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->first();

        if (! $token) {
            return new JsonResponse(['message' => 'Error when change password'], Response::HTTP_BAD_REQUEST);
        }

        $user = auth()->user();
        $user->password = $data['password'];
        $user->save();

        return new JsonResponse([
            'message' => 'Password changed'
        ], Response::HTTP_OK);
    }
}
