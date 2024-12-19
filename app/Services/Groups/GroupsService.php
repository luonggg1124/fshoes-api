<?php

namespace App\Services\Groups;

use App\Http\Resources\GroupResource;
use App\Repositories\Groups\GroupsRepositoryInterface;
use GuzzleHttp\Utils;
use Illuminate\Database\QueryException;
use Mockery\Exception;
use function GuzzleHttp\json_encode;

class GroupsService implements GroupsServiceInterface
{

    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    private mixed $database;

    public function __construct(protected GroupsRepositoryInterface $groupsRepository)
    {
        $this->database = app('firebase.database');
    }


    function getAll()
    {
        $groups = $this->groupsRepository->query()->withTrashed()->get();
        return GroupResource::collection(
            $groups
        );
    }

    function findById(int|string $id)
    {

        $group = $this->groupsRepository->query()->find($id);
        if ($group) return GroupResource::make($group);
        else throw new Exception(__('messages.error-not-found'));

    }

    function create(array $data, array $option = [])
    {
            try{
                $group =  $this->groupsRepository->create([
                    'group_name' => $data['group_name'],
                    'permissions' => Utils::jsonEncode($data['permissions'] ?? ""),
                ]);

                 $this->database->getReference('groups/'. $group->id)->set(json_encode($data["permissions"] ?? ""));

                return response()->json(["message" => __('messages.created-success'), "group"=>GroupResource::make($group)], 201);
            }catch (QueryException  $exception){
                if ($exception->getCode() == '23000') {
                    return response()->json(["message"=> __('messages.group.error-group')] , 500);
                }
            }
    }

    function update(int|string $id, array $data, array $option = [])
    {
        try{
            $group =  $this->groupsRepository->find($id);
            if($group){
                if(isset($data["permissions"])){
                    $this->database->getReference('groups/')->getChild($group->id)->remove();
                    $this->database->getReference('groups/'. $id)->set(json_encode($data["permissions"]));
                }
                $group->update($data);
                return GroupResource::make($group);
            }else return response()->json(["message"=> __('messages.error-not-found')], 404);

        }catch (QueryException $exception){
            if ($exception->getCode() == '23000') {
                return response()->json(["message"=> __('messages.group.error-group')] , 500);
            }
            return response()->json(["message"=> $exception->getMessage()], 500);
        }
    }

    function delete(int|string $id)
    {
        $group = $this->groupsRepository->query()->find($id);
        if ($group) {
            $this->database->getReference('groups/'. $group->id)->remove();
             $group->delete();
        }
        else throw new Exception(__('messages.error-not-found'));
    }

    function restore(int|string $id)
    {
        $group = $this->groupsRepository->query()->withTrashed()->find($id);
        if ($group) {
            $group->restore();
        }
        else throw new Exception(__('messages.error-not-found'));
    }

    function forceDelete(int|string $id)
    {
        $group = $this->groupsRepository->query()->withTrashed()->find($id);
        if ($group) {
            $this->database->getReference('groups/'. $group->id)->remove();
            $group->forceDelete();
        }
        else throw new Exception(__('messages.error-not-found'));
    }
}
