<?php

namespace App\Http\Controllers\Api;

use Mockery\Exception;
use Illuminate\Http\Request;
use App\Http\Traits\Cloudinary;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Services\Post\PostServiceInterface;

class PostsController extends Controller
{
    use Cloudinary;
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
       $infor =  $this->uploadImageCloudinary($request->file("theme") , 'posts');
        $data =[
            "title"=>$request->title,
            "slug"=>$request->slug,
            "content"=>$request->content,
            "topic_id"=>$request->topic_id,
            "author_id"=>$request->author_id,
            "theme"=>$infor["path"],
            "public_id"=>$infor["public_id"]
        ];
       return  $this->postService->create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        return  $this->postService->findBySlug($slug);

    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id)
    {
        $data = $request->all();
        unset($data["_method"]);
         unset($data["theme"]);
        if($request->hasFile("theme")){
            $infor =  $this->uploadImageCloudinary($request->file("theme") , 'posts');
            $data["theme"] = $infor["path"];
            $data["public_id"] = $infor["public_id"];
        }
    
        return  $this->postService->update($id , $data);
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
