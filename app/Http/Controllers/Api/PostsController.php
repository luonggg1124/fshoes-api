<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Services\Post\PostServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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
        if (auth('api')->check() && auth('api')->user()->group_id > 1 && !Gate::allows('post.view')) {
            return response()->json(["message" => "You are not allowed to do this action."], 403);
        }
        return $this->postService->getAll($request->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('post.restore'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
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
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('post.update'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
        return  $this->postService->update($id , $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('post.delete'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
        return $this->postService->delete($id);
    }

    public function restore(string $id)
    {
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('post.restore'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
        return   $this->postService->restore($id);

    }

    public function forceDelete(string $id)
    {
        if(!auth('api')->check() || auth('api')->user()->group_id <=1 ||  !Gate::allows('post.forceDelete'))      {
            return response()->json(["message"=>"You are not allowed to do this action."],403);
        }
        return $this->postService->forceDelete($id);
    }
}
