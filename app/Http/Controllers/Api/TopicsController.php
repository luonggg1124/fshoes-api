<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Topic\TopicsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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
        if(auth('api')->check() && auth('api')->user()->group_id >1 &&  !Gate::allows('topic.view'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }

        return response()->json(
            $this->topicsService->getAll($request->all()), 200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('topic.create'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }

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
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('topic.update'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
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
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('topic.delete'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }

        try {
            $this->topicsService->delete($id);
            return response()->json(['message' => "Deleted topic"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function restore(string $id)
    {
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('topic.restore'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }

        try {
            $this->topicsService->restore($id);
            return response()->json(['message' => "Restored topic"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function forceDelete(string $id)
    {
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('topic.forceDelete'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }

        try {
            $this->topicsService->forceDelete($id);
            return response()->json(['message' => "Force deleted topic"], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
