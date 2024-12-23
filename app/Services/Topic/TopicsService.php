<?php

namespace App\Services\Topic;

use App\Http\Resources\GroupResource;
use App\Http\Resources\TopicsResource;
use App\Repositories\Topic\TopicsRepositoryInterface;
use Mockery\Exception;

class TopicsService implements TopicServiceInterface
{

    public function __construct(protected TopicsRepositoryInterface $topicsRepository)
    {
    }


    function getAll(array $params)
    {
        $topics = $this->topicsRepository->query()->withTrashed()->paginate(5);
        return TopicsResource::collection(
            $topics
        );
    }

    function findById(int|string $id)
    {
        $topic = $this->topicsRepository->query()->find($id);
        if ($topic) return TopicsResource::make($topic);
        else throw new Exception(__('messages.error-not-found'));

    }

    function create(array $data, array $option = [])
    {
        try {
            $topic = $this->topicsRepository->create([
                'topic_name' => $data['topic_name'],
                'slug' => $data['slug'],
                'parent_topic_id' => isset($data['parent_topic_id']) ? $data['parent_topic_id'] : null
            ]);
            return TopicsResource::make($topic);
        } catch (Exception $exception) {
            throw new Exception("");
        }
    }

    function update(int|string $id, array $data, array $option = [])
    {
        try{
            $topic =  $this->topicsRepository->update($id, $data);
            return TopicsResource::make($topic);
        }catch (Exception $exception){
            throw new Exception("");
        }
    }

    function delete(int|string $id)
    {
        $topic = $this->topicsRepository->query()->find($id);
        if ($topic) {
            $topic->delete();
        } else throw new Exception(__('messages.topic.error-can-not'));
    }

    function restore(int|string $id)
    {
        $topic = $this->topicsRepository->query()->withTrashed()->find($id);
        if ($topic) {
            $topic->restore();
        } else throw new Exception(__('messages.topic.error-restore'));
    }

    function forceDelete(int|string $id)
    {
        $topic = $this->topicsRepository->query()->withTrashed()->find($id);
        if ($topic) {
            $topic->forceDelete();
        } else throw new Exception(__('messages.topic.error-force-delete'));
    }
}
