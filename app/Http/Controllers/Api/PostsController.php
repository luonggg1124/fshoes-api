<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Services\Post\PostServiceInterface;
use Illuminate\Http\Request;
use Mockery\Exception;

class PostsController extends Controller
{

    public function __construct(protected PostServiceInterface $postService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->postService->getAll($request->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
       return  $this->postService->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return  $this->postService->findById($id);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, string $id)
    {

        return  $this->postService->update($id , $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->postService->delete($id);
    }

    public function restore(string $id)
    {

        return   $this->postService->restore($id);

    }

    public function forceDelete(string $id)
    {
        return $this->postService->forceDelete($id);
    }
}
