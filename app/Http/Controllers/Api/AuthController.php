<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'device_name' => 'required|string|max:255',
            'fc_token' => 'required|string',
        ]);

        $user = User::query()
            ->where('phone', $request->input('phone'))
            ->orWhere('email', $request->input('phone'))
            ->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'phone' => [__('auth.failed')],
            ]);
        }

        $user->update(['fc_token' => $request->input('fc_token')]);

        $token = $user->createToken($request->input('device_name'))->plainTextToken;
        return response()->json([
            'token' => $token,
            'data' => $user
        ]);
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        unset($data['password']);

        $user = User::create([
            'password' => BCrypt($request->input('password')),
            'locations' => [$request->input('location')]
        ] + $data);

        $token = $user->createToken($request->input('device_name'))->plainTextToken;
        return response()->json([
            'token' => $token,
            'data' => $user
        ], 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['msg' => __('auth.logout')]);
    }
}
