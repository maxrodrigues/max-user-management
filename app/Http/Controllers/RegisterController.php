<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();
        if (! empty($user)) {
            return new JsonResponse(['message' => 'Error when register new user'], Response::HTTP_BAD_REQUEST);
        }

        User::create($data);

        $token = auth()->attempt([$data['email'], $data['password']]);

        return new JsonResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('jwt.ttl'),
        ], Response::HTTP_CREATED);
    }
}
