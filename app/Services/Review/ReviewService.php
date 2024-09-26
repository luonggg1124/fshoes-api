<?php 
namespace App\Services\Review;
use App\Repositories\Review\ReviewRepositoryInterface;

class ReviewService implements ReviewServiceInterface{
    public function __construct(
        protected ReviewRepositoryInterface $reviewRepository,
    ){}
   
}