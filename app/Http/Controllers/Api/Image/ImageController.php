<?php

namespace App\Http\Controllers\Api\Image;

use App\Http\Controllers\Controller;

use App\Services\Image\ImageServiceInterface;
use Illuminate\Http\Request;

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

        }catch (\Throwable $throwable){

        }
    }
}
