<?php

namespace App\Services\Category;



use App\Http\Resources\CategoryResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Cloudinary;
use App\Http\Traits\Paginate;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;


class CategoryService implements CategoryServiceInterface
{
    use CanLoadRelationships, Cloudinary,Paginate;
    private array $relations = ['products','parents','children'];
    private array $columns = ['name', 'slug', 'parent_id','created_at','updated_at'];
    public function __construct(protected CategoryRepositoryInterface $categoryRepository) {}

    public function getAll()
    {
        $perPage = request()->query('per_page');
        $column = request()->query('column') ?? 'id';
        if(!in_array($column,$this->columns)) $column = 'id';
        $sort = request()->query('sort') ?? 'desc';
        if($sort !== 'desc' && $sort !== 'asc') $sort = 'asc';
        $categories = $this->loadRelationships($this->categoryRepository->query()->orderBy($column,$sort))->paginate($perPage);
        //return $categories;
        return [
            'paginator' => $this->paginate($categories),
            'data' => CategoryResource::collection(
                $categories->items()
            ),


        ];
    }
    public function mains(){
        $perPage = request()->query('per_page');
        $column = request()->query('column') ?? 'id';
        if(!in_array($column,$this->columns)) $column = 'id';
        $sort = request()->query('sort') ?? 'asc';
        if($sort !== 'desc' && $sort !== 'asc') $sort = 'asc';
        $categories = $this->loadRelationships($this->categoryRepository->query()->where('is_main',1)->orderBy($column,$sort))->paginate($perPage);
        return [
            'paginator' => $this->paginate($categories),
            'data' => CategoryResource::collection(
                $categories->items()
            ),
        ];
    }
    public function findById(int|string $id)
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw new ModelNotFoundException('Category not found');
        }
        $category = $this->loadRelationships($category);
        return new CategoryResource($category);
    }

    /**
     * @throws \Exception
     */
    public function create(array $data, array $option = [
        'parents' => []
    ])
    {
        $category = $this->categoryRepository->create($data);
        if(!$category) throw new \Exception('Category not found');
        $listPar = [];
        if(count($option['parents']) > 0){
            foreach ($option['parents'] as $parent){
                $parCate = $this->categoryRepository->find($parent);
                if($parCate && $parCate->is_main == 1) $listPar[] = $parCate->id;
            }
        }
        $category->parents()->attach($listPar);
        $category->slug = $this->slug($category->name,$category->id);
        $category->save();
        $category = $this->loadRelationships($category);
        return new CategoryResource($category);
    }

    public function update(int|string $id, array $data, array $option = [
        'parents' => []
    ])
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) throw new ModelNotFoundException('Category not found');

        $category->update($data);
        $listPar = [];
        if(count($option['parents']) > 0){
            foreach ($option['parents'] as $parent){
                $parCate = $this->categoryRepository->find($parent);
                if($parCate && $parCate->is_main == 1) $listPar[] = $parCate->id;
            }
        }
        $category->parents()->sync($listPar);
        $category->slug = $this->slug($category->name,$category->id);
        $category->save();
        return new CategoryResource($this->loadRelationships($category));
    }
    public function delete(int|string $id)
    {
        try {
            $category = $this->categoryRepository->find($id);
            if ($category->delete_at) {
                return false;
            }
            $category->delete($id);
        } catch (\Exception $e) {
            throw new \Exception('Cannot delete category');
        }
    }
    public function forceDelete(int|string $id)
    {
        try {
            $category = $this->categoryRepository->find($id);
            if ($category->image_url) {
                $this->deleteImageCloudinary($category->public_id);
            }
            $category->forceDelete($id);
        } catch (\Exception $e) {
            throw new \Exception('Cannot delete category');
        }
    }
    protected function slug(string $name, int|string $id){
        $slug = Str::slug($name).'.'.$id;
        return $slug;
    }
}
