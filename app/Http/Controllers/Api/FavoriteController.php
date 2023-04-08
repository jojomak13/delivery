<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\FavoritesResource;
use App\Models\Branch;
use App\Models\Favorite;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'favorable_type' => 'required|in:product,bundle,store'
        ]);

        $type = Favorite::TYPES[$request->input('favorable_type')];

        $favorites = $request->user()
                ->favorites()
                ->where('favorable_type', $type)
                ->with('favorable')
                ->get();

        return response()->json(FavoritesResource::collection($favorites));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'favorable_id' => 'required',
            'favorable_type' => 'required|in:product,bundle,store',
            'branch_id' => 'required',
        ]);

        $type = Favorite::TYPES[$request->input('favorable_type')];
        
        
        $exists = $request->user()->favorites()
            ->where('favorable_id', $request->input('favorable_id'))
            ->where('favorable_type', $type)
            ->count();
        
        if($exists){
            return response()->json([
                'msg' => __('app.favorite.exists')
            ]);
        }
        
        $item = $type::findorFail($request->input('favorable_id'));
        
        abort_unless($this->isValidBranch($type, $item), 422);

        $favorite = $request->user()->favorites()->create([
            'favorable_id' => $request->input('favorable_id'),
            'favorable_type' => $type,
            'branch_id' => $request->input('branch_id'),
        ]);

        return response()->json([
            'data' => $favorite,
            'msg' => __('app.favorite.added')
        ]);
    }

    private function isValidBranch($type, $item): bool
    {
        $branch = Branch::findOrFail(request()->input('branch_id')); 

        if($type === Store::class){
            return $item->id === $branch->store_id;
        }

        return $item->store_id === $branch->store_id;
    }

    public function destroy(Request $request, Favorite $favorite)
    {
        abort_if($favorite->user_id !== $request->user()->id, 404);

        $favorite->delete();

        return response()->json([
            'msg' => __('app.favorite.deleted')
        ]);
    }

}
