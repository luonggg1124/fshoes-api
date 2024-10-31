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
            $this->groupsService->getAll($request->all()), 200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->groupsService->create($request->all());
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
        return $this->groupsService->update($id, $request->all());
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
