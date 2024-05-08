<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = auth()->user();
        return new JsonResponse(['profile' => $user], Response::HTTP_OK);
    }
}
