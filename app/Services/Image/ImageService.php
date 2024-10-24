<?php

namespace App\Services\Image;

use App\Http\Resources\ImageResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Traits\Cloudinary;
use App\Http\Traits\Paginate;
use App\Models\Product;
use App\Repositories\Image\ImageRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class ImageService implements ImageServiceInterface
{
    use Cloudinary, CanLoadRelationships, Paginate;

    private array $relations = ['products', 'variations'];
    private array $columns = [
        'id',
        'url',
        'public_id',
        'alt_text',
        'created_at',
        'updated_at',
    ];

    public function __construct(
        protected ImageRepositoryInterface $repository
    )
    {
    }

    public function all()
    {
        $perPage = request()->query('per_page');

        $image = $this->loadRelationships($this->repository->query()->sortByColumn(columns:$this->columns)->latest())->paginate($perPage);
        return [
            'paginator' => $this->paginate($image),
            'data' => ImageResource::collection(
                $image->items()
            ),
        ];
    }

    public function createMany(array $images, string $folder = '')
    {
        $list = [];
        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $img = $this->create($image, 'assets');
                $list[] = $img;
            }
        }
        if (count($list) < 1) throw new Exception('Cannot create images.Maybe the image is in the wrong format!');
        return ImageResource::collection($list);
    }

    public function create(UploadedFile $file, string $folder = '')
    {
        $upload = $this->uploadImageCloudinary($file, $folder);
        $image = $this->repository->create([
            'url' => $upload['path'],
            'public_id' => $upload['public_id'],
            'alt_text' => $folder
        ]);
        if (!$image) {
            throw new \Exception('Cannot create image');
        }

        return $image;
    }

    public function destroy(int|string $id)
    {
        $image = $this->repository->find($id);
        if (!$image) throw new ModelNotFoundException('Image not found');
        $exists = Storage::disk('cloudinary')->exists($image->public_id);
        if ($exists) $this->deleteImageCloudinary($image->public_id);
        $image->delete();
        return true;
    }

    public function destroyMany(array $ids)
    {
        foreach ($ids as $id) {
            $image = $this->repository->find($id);
            if (!$image) throw new ModelNotFoundException('Image ' . $id . ' not found');
            $exists = Storage::disk('cloudinary')->exists($image->public_id);
            if ($exists) $this->deleteImageCloudinary($image->public_id);
            $image->delete();
        }
        return true;
    }
}
