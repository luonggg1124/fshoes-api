<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Topic\TopicsService;
use Illuminate\Http\Request;
use Mockery\Exception;

class TopicsController extends Controller
{
    public function __construct(protected TopicsService $topicsService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json(
            $this->topicsService->getAll($request->all()), 200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $topic = $this->topicsService->create($request->all());
            return response()->json(['message' => "Create topic successfully",
                "topic" => $topic], 201);
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
            return response()->json($this->topicsService->findById($id), 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            return response()->json([
                "message"=>"Update topic successfully",
                "topic" => $this->topicsService->update($id, $request->all())
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->topicsService->delete($id);
            return response()->json(['message' => "Deleted topic"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function restore(string $id)
    {
        try {
            $this->topicsService->restore($id);
            return response()->json(['message' => "Restored topic"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function forceDelete(string $id)
    {
        try {
            $this->topicsService->forceDelete($id);
            return response()->json(['message' => "Force deleted topic"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
