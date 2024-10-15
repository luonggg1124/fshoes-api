<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Groups\GroupsServiceInterface;
use Illuminate\Http\Request;
use Mockery\Exception;
use function GuzzleHttp\json_decode;

class GroupsController extends Controller
{
    public function __construct(protected GroupsServiceInterface $groupsService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json(
            $this->groupsService->getAll($request), 200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $group = $this->groupsService->create($request->all());
            return response()->json(['message' => "Create group successfully",
                                "group" => $group], 201);
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
            return response()->json($this->groupsService->findById($id), 200);
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
                "message"=>"Update group successfully",
                "group" => $this->groupsService->update($id, $request->all())
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
            $this->groupsService->delete($id);
            return response()->json(['message' => "Deleted group"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function restore(string $id)
    {
        try {
            $this->groupsService->restore($id);
            return response()->json(['message' => "Restored group"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function forceDelete(string $id)
    {
        try {
            $this->groupsService->forceDelete($id);
            return response()->json(['message' => "Force deleted group"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
