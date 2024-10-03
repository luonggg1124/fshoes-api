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
}
