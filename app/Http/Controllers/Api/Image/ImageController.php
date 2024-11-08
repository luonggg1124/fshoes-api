<?php

namespace App\Http\Controllers\Api\Image;

use App\Http\Controllers\Controller;

use App\Services\Image\ImageServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;


class ImageController extends Controller
{
    public function __construct(
        protected ImageServiceInterface $service
    ){}
    public function index(){
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('image.view'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }

        return response()->json(
            $this->service->all()
        );
    }

    public function store(Request $request){
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('image.create'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }

        try {
            $images = $request->images;
            $list = [];
            if(empty($images)){
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot find any images',
                ],500);
            }
            if(is_array($images)){
                $list = $this->service->createMany($images);
            }
            if(empty($list) || count($list) <1){
                return response()->json([
                    'success' => false,
                    'message' => 'No images uploaded'
                ],500);
            }
            return response()->json([
                'status' => true,
                'images' => $list
            ],201);

        }catch (\Throwable $throwable){
            Log::error(
                message: __CLASS__.'@'.__FUNCTION__,context: [
                'line' => $throwable->getLine(),
                'message' => $throwable->getMessage()
            ]
            );
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
            ],500);
        }
    }
    public function destroy(int|string $id){
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('image.delete'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }

        try {
            $success = $this->service->destroy( $id);
            return \response()->json([
                'status' => $success,
                'message' => 'Deleted successfully!'
            ],201);
        }catch (\Throwable $throwable) {
            return \response()->json([
                'status' => false,
                'error' => $throwable->getMessage()
            ],404);
        }
    }
    public function destroyMany(Request $request){
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('image.delete'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }

        try {
            $images = $request->images;
            if(empty($images || count($images) < 1)){
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot find any images to delete.'
                ],400);
            }
            $success = $this->service->destroyMany($images);
            return \response()->json([
                'status' => $success,
                'message' => 'Deleted successfully!'
            ],201);
        }catch (\Throwable $throwable) {
            return \response()->json([
                'status' => false,
                'error' => $throwable->getMessage()
            ],404);
        }
    }
}
