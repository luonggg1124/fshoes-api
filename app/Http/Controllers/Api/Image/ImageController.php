<?php

namespace App\Http\Controllers\Api\Image;

use App\Http\Controllers\Controller;

use App\Services\Image\ImageServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ImageController extends Controller
{
    public function __construct(
        protected ImageServiceInterface $service
    ){}
    public function index(){
        return response()->json(
            $this->service->all()
        );
    }

    public function store(Request $request){
        try {
            $images = $request->images;
            $list = [];
            if(is_array($images)){
                $list = $this->service->createMany($images);
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
}
