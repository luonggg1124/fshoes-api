<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Services\Groups\GroupsServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Validator;
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
    public function store(GroupRequest $request)
    {
        if (!auth('api')->check() || auth('api')->user()->group_id <= 1 || !Gate::allows('group.create')) {
            return response()->json(["message" => "You are not allowed to do this action."], 403);
        }
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
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('group.update'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
        return $this->groupsService->update($id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('group.delete'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
        try {
            $this->groupsService->delete($id);
            return response()->json(['message' => "Deleted group"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function restore(string $id)
    {
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('group.restore'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
        try {
            $this->groupsService->restore($id);
            return response()->json(['message' => "Restored group"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function forceDelete(string $id)
    {
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('group.forceDelete'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
        try {
            $this->groupsService->forceDelete($id);
            return response()->json(['message' => "Force deleted group"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
