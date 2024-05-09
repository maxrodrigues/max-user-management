<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = auth()->user();
        return new JsonResponse(['profile' => $user], Response::HTTP_OK);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = auth()->user();
        $user->fill($request->validated());
        $user->save();

        return new JsonResponse(['message' => 'Profile updated'], Response::HTTP_OK);
    }
}
