<?php

namespace App\Http\Controllers\Api\Attribute;

use App\Http\Controllers\Controller;
use App\Services\Attribute\AttributeServiceInterface;
use Illuminate\Http\Request;

class AttributeController extends Controller
{

    public function __construct(protected AttributeServiceInterface $attributeService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(int|string $aid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int|string $aid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int|string $aid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int|string $aid)
    {
        //
    }
}
