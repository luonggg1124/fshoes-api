<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        return response()->json($this->postService->getAll($request->all()), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $post = $this->postService->create($request->all());
            return response()->json(['message' => "Create post successfully",
                "post" => $post], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post = $this->postService->findById($id);
            return response()->json($post, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
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
        try {
            $this->postService->delete($id);
            return response()->json(['message' => "Deleted post"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function restore(string $id)
    {
        try {
            $this->postService->restore($id);
            return response()->json(['message' => "Restored post"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function forceDelete(string $id)
    {
        try {
            $this->postService->forceDelete($id);
            return response()->json(['message' => "Force deleted post"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
