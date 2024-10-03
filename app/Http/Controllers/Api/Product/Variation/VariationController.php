<?php

namespace App\Http\Controllers\Api\Product\Variation;

use App\Http\Controllers\Controller;
use App\Services\Product\Variation\VariationService;
use Illuminate\Http\Request;

class VariationController extends Controller
{
    public function __construct(
        protected VariationService $service
    ){}
    public function index(string|int $pid){
        return response()->json([
           'success' => true,
           'variations' => $this->service->index($pid)
        ]);
    }
    public function store(string|int $pid, Request $request){

    }
}
