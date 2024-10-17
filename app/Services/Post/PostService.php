<?php

namespace App\Services\Post;

use App\Http\Resources\PostResource;
use App\Http\Resources\TopicsResource;
use App\Repositories\Post\PostRepositoryInterface;
use Mockery\Exception;

class PostService implements PostServiceInterface
{

    public function __construct(protected PostRepositoryInterface $postRepository)
    {
    }


    function getAll(array $params)
    {
        $posts = $this->postRepository->all();
        return PostResource::collection($posts);
    }

    function findById(int|string $id)
    {
      $post = $this->postRepository->find($id);
      return new PostResource($post);
    }

    function findByUserId(int|string $id)
    {
        $post = $this->postRepository->query()->where('author_id', $id)->get();
        return new PostResource($post);
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
            ]);
            return PostResource::make($post);
        } catch (Exception $exception) {
            throw new Exception("");
        }
    }

    function update(int|string $id, array $data, array $option = [])
    {
        // TODO: Implement update() method.
    }

    function delete(int|string $id)
    {
        $post = $this->postRepository->query()->find($id);
        if ($post) {
            $post->delete();
        } else throw new Exception("Can't find post");
    }

    function restore(int|string $id)
    {
        $post = $this->postRepository->query()->withTrashed()->find($id);
        if ($post) {
            $post->restore();
        } else throw new Exception("Can't restore post");
    }

    function forceDelete(int|string $id)
    {
        $topic = $this->postRepository->query()->withTrashed()->find($id);
        if ($topic) {
            $topic->forceDelete();
        } else throw new Exception("Can't force delete post");
    }
}
