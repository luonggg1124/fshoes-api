<?php 
namespace App\Repositories\Review;

use App\Models\Review;
use App\Repositories\BaseRepository;

class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface{
    public function __construct(
        Review $model
    ){
        parent::__construct($model);
    }
    
}