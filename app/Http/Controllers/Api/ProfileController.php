<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'locations' => $user->locations,
        ]);
    }

    /**
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $data = $request->validated();

        unset($data['password']);

        if($request->input('password'))
            $data['password'] = BCrypt($request->input('password'));

        $request->user()->update($data);

        return response()->json([
            'status' => true,
            'msg' => __('app.profile.updated')
        ]);
    }
}
