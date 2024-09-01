<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductImages\ProductImagesServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as Status;

class ProductImagesController extends Controller
{
    protected $productImageService;
    public function __construct(ProductImagesServiceInterface $productImageService) {
        $this->productImageService = $productImageService;
    }
    public function index()
    {
        $allImage =  $this->productImageService->getAllImage();
        return response()->json($allImage , Status::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->productImageService->deleteImage($id);
    }
}
