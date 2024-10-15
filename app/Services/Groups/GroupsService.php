<?php

namespace App\Services\Groups;

use App\Http\Resources\GroupResource;
use App\Repositories\Groups\GroupsRepositoryInterface;
use Mockery\Exception;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class GroupsService implements GroupsServiceInterface
{

    public function __construct(protected GroupsRepositoryInterface $groupsRepository)
    {
    }


    function getAll()
    {
        $groups = $this->groupsRepository->query()->withTrashed()->paginate(5);
        return GroupResource::collection(
            $groups
        );
    }

    function findById(int|string $id)
    {

        $group = $this->groupsRepository->query()->find($id);
        if ($group) return GroupResource::make($group);
        else throw new Exception("Group not found");

    }

    function create(array $data, array $option = [])
    {
            try{
                $group =  $this->groupsRepository->create([
                    'group_name' => $data['group_name'],
                    'permissions' => json_encode($data['permissions']),
                ]);
                return GroupResource::make($group);
            }catch (Exception $exception){
                throw new Exception("");
            }
    }

    function update(int|string $id, array $data, array $option = [])
    {
        try{
            $group =  $this->groupsRepository->update($id, $data);
            return GroupResource::make($group);
        }catch (Exception $exception){
            throw new Exception("");
        }
    }

    function delete(int|string $id)
    {
        $group = $this->groupsRepository->query()->find($id);
        if ($group) {
             $group->delete();
        }
        else throw new Exception("Group not found");
    }

    function restore(int|string $id)
    {
        $group = $this->groupsRepository->query()->withTrashed()->find($id);
        if ($group) {
            $group->restore();
        }
        else throw new Exception("Group not found");
    }

    function forceDelete(int|string $id)
    {
        $group = $this->groupsRepository->query()->withTrashed()->find($id);
        if ($group) {
            $group->forceDelete();
        }
        else throw new Exception("Group not found");
    }
}
