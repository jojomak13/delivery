<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
            'code' => 'required',
        ]);

        $code = PromoCode::query()
            ->where('store_id', $request->input('store_id')) 
            ->where('name', $request->input('code'))
            ->firstOrFail();

        abort_if($code->status === PromoCode::FINISHED, 422, __('app.code.finished'));

        abort_if($code->status === PromoCode::EXPIRED, 422, __('app.code.expired'));

        return response()->json([
            'msg' => __('app.code.available'),
            'data' => $code
        ]);
    }
}
