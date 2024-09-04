<?php

namespace App\Services\Category;



use App\Http\Resources\CategoryResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Cloudinary;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class CategoryService implements CategoryServiceInterface
{
    use CanLoadRelationships,Cloudinary;
    private array $relations = ['products'];
    public function __construct(protected CategoryRepositoryInterface $categoryRepository){}

    public function getAll(): AnonymousResourceCollection
    {
        $categories = $this->loadRelationships($this->categoryRepository->query()->latest());
       return CategoryResource::collection(
           $categories->paginate()
       );
    }

    public function findById(int|string $id)
    {
        $category = $this->loadRelationships($this->categoryRepository->find($id));

        if(!$category){
            throw new ModelNotFoundException('Category not found');
        }
        return new CategoryResource($category);
    }

    /**
     * @throws \Exception
     */
    public function create(array $data, array $option = []){
        try {
            $category = $this->categoryRepository->create($data);
            if($option['image']){
                $upload = $this->uploadImageCloudinary($option['image']);
                $category->image_url = $upload['path'];
                $category->public_id = $upload['public_id'];
                $category->save();
            }
            return new CategoryResource($category);
        }catch (\Exception $e){
            throw new \Exception('Cannot create category');
        }

    }
    public function update(int|string $id ,array $data,array $option = []){
        try {
            $category = $this->categoryRepository->update($id,$data);
            if($option['image']){
                if($category->image_url){
                    $this->deleteImageCloudinary($category->public_id);
                }
                $upload = $this->uploadImageCloudinary($option['image']);
                $category->image_url = $upload['path'];
                $category->public_id = $upload['public_id'];
                $category->save();
            }
            return new CategoryResource($this->loadRelationships($category));
        }catch (\Exception $e){
            throw new \Exception('Cannot update category');
        }
    }
   public function delete(int|string $id)
   {
       try {
           $category = $this->categoryRepository->find($id);
           if($category->delete_at){
              return false;
           }
           $category->delete($id);

       }catch (\Exception $e){
           throw new \Exception('Cannot delete category');
       }
   }
    public function forceDelete(int|string $id)
    {
        try {
            $category = $this->categoryRepository->find($id);
            if($category->image_url){
                $this->deleteImageCloudinary($category->public_id);
            }
            $category->forceDelete($id);
        }catch (\Exception $e){
            throw new \Exception('Cannot delete category');
        }
    }
}
