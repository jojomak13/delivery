<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

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
     * @param Client $twilio
     * @return JsonResponse
     * @throws TwilioException
     */
    public function forget(Request $request, Client $twilio): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|string|max:255'
        ]);

        $user = User::query()
                ->where('email', $request->input('email'))
                ->firstOrFail();

        try {
            $verification = $twilio->verify->v2->services(config('services.twilio.verify_sid'))
                ->verifications
                ->create($user->phone, 'sms');

            $sentSuccess = $verification->status === 'pending';
        } catch(TwilioException $e){
            return response()->json([
                'status' => false,
                'msg' => __('app.auth.invalid_otp')
            ], 400);
        }

        return response()->json([
            'status' => $sentSuccess,
            'msg' => $sentSuccess? __('app.auth.otp_sent_successfully') : __('app.auth.otp_sent_failed')
        ], $sentSuccess? 200 : 500);
    }

    /**
     * @param Request $request
     * @param Client $twilio
     * @return JsonResponse
     */
    public function reset(Request $request, Client $twilio): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|string|max:255',
            'otp' => 'required|string|max:255',
            'password' => 'required|string|max:255|confirmed',
        ]);

        $user = User::query()
            ->where('email', $request->input('email'))
            ->firstOrFail();

        try {
            $verification = $twilio->verify->v2->services(config('services.twilio.verify_sid'))
                ->verificationChecks
                ->create(['To' => $user->phone, 'Code' => $request->input('otp')]);

            $isValid = $verification->status === 'approved';
        } catch (TwilioException $e) {
            if($e->getCode() != 20404){
                Log::error($e->getMessage());
            }

            return response()->json([
                'status' => false,
                'msg' => __('app.auth.invalid_otp')
            ], 400);
        }

        if($isValid)
            $user->update(['password' => Bcrypt($request->input('password'))]);

        return response()->json([
            'status' => $isValid,
            'msg' => $isValid? __('app.auth.password_reset_successfully') : __('app.auth.otp_sent_failed'),
        ], $isValid? 201 : 400);
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
