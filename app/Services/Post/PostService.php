<?php

namespace App\Services\Post;

use Mockery\Exception;
use App\Http\Traits\Cloudinary;
use App\Http\Resources\PostResource;
use Illuminate\Database\QueryException;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\Image\ImageRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostService implements PostServiceInterface
{
    public function __construct(protected PostRepositoryInterface $postRepository)
    {
    }


    function getAll(array $params)
    {
        $posts = $this->postRepository->query()->withTrashed()->get();
        return response()->json(PostResource::collection($posts), 200);
    }

    function findById(int|string $id)
    {
        try{
            $post = $this->postRepository->query()->findOrFail($id);
            $post->views++;
            $post->save();
            return response()->json(PostResource::make($post), 200);
        }catch(ModelNotFoundException $e){
            return response()->json(['message' => "Post not found"], 404);
        }
    }

    function findByUserId(int|string $id)
    {
        try{
            $post = $this->postRepository->query()->where('author_id', $id)->get();
            return response()->json(PostResource::make($post), 200);
        }catch(ModelNotFoundException $e){
            return response()->json(['message' => "Post not found"], 404);
        }
    }

    function create(array $data, array $option = [])
    {
        try {
            $post = $this->postRepository->create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'content' => $data['content'],
                'topic_id' => $data['topic_id'],
                'author_id' => $data['author_id'],
                "theme"=>$data['theme'],
                "public_id" => $data['public_id']
            ]);

            return PostResource::make($post);
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23000') {
                return response()->json(['message' => "The title or slug already exists. Please choose a different value."], 422);
            }
            return response()->json(['message' => "Something went wrong. Please try again later."], 500);
        } catch (Exception $exception) {
            // Handle general exceptions
            return response()->json("An unexpected error occurred. Please try again.", 500);
        }

    }

    function update(int|string $id, array $data, array $option = [])
    {
        try {
            $post = $this->postRepository->find($id);
            $post->update($data);
            return PostResource::make($post);
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23000') {
                return response()->json(['message' => "The title or slug already exists. Please choose a different value."], 422);
            }
            return response()->json(['message' => "Something went wrong. Please try again later."], 500);
        } catch (Exception $exception) {
            return response()->json("An unexpected error occurred. Please try again.", 500);
        }
    }

    function delete(int|string $id)
    {
        try {
            $post = $this->postRepository->query()->find($id);
            if ($post) {
                $post->delete();
                return response()->json(['message' => 'Post deleted successfully.'], 200);
            } else {
                throw new ModelNotFoundException("Can't find post");
            }
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => $exception->getMessage()], 404);
        } catch (Exception $exception) {
            return response()->json(['error' => 'An error occurred while deleting the post.'], 500);
        }
    }

    function restore(int|string $id)
    {
        try {
            $post = $this->postRepository->query()->withTrashed()->find($id);
            if ($post) {
                $post->restore();
                return response()->json(['message' => 'Post restored successfully.'], 200);
            } else {
                throw new ModelNotFoundException("Can't restore post");
            }
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => $exception->getMessage()], 404);
        } catch (Exception $exception) {
            return response()->json(['error' => 'An error occurred while restoring the post.'], 500);
        }
    }

    function forceDelete(int|string $id)
    {
        try {
            $post = $this->postRepository->query()->withTrashed()->where('id', $id)->first();
            if ($post) {
                $post->forceDelete();
                return response()->json(['message' => 'Post permanently deleted successfully.'], 200);
            } else {
                return response()->json(["message"=>"Post not found"] , 500);
            }
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => $exception->getMessage()], 404);
        } catch (Exception $exception) {
            return response()->json(['error' => 'An error occurred while force deleting the post.'], 500);
        }
    }

}
