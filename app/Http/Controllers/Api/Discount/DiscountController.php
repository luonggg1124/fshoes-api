<?php

namespace App\Http\Controllers\Api\Discount;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discount\CreateSaleRequest;
use App\Services\Discount\DiscountServiceInterface;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function __construct(protected DiscountServiceInterface $service){}

    public function index(){}

    public function addSale(CreateSaleRequest $request){

    }
}
