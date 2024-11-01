<?php

namespace App\Http\Controllers\Api\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\CreateReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Http\Resources\Review\ReviewResource;
use App\Services\Review\ReviewServiceInterface;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function __construct(protected ReviewServiceInterface $reviewService)
    {
    }

    /**
     * Display a listing of the reviews.
     */
    public function index(): Response|JsonResponse
    {
        return \response()->json([
            $this->reviewService->all()
        ]);
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(CreateReviewRequest $request): JsonResponse
    {
        try {
            $review = $this->reviewService->create($request->validated());
            return response()->json([
                'message' => 'Review created successfully',
                'review' => $review
            ], 201);
        } catch (\Exception $e) {
            Log::error('Some thing went wrong!', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified review.
     */
    public function show(int|string $id): Response|JsonResponse
    {
        try {
            return response()->json($this->reviewService->find($id));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified review in storage.
     */
    public function update(UpdateReviewRequest $request, string|int $id)
    {
        try {
            $review = $this->reviewService->update($id, $request->validated());
            return response()->json([
                'message' => 'Review created successfully',
                'review' => $review
            ], 201);
        } catch (\Exception $e) {
            Log::error('Some thing went wrong!', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified review from storage.
     */
    public function destroy(int|string $id): JsonResponse
    {
        try {
            $this->reviewService->delete($id);
            return response()->json([
                'message' => 'Review deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function toggleLike(Request $request, int $review_id): JsonResponse
    {
        try {
            $user_id = request()->user()->id;  // Lấy ID người dùng hiện tại
            // $user_id = 1;

            // Gọi đến service để xử lý toggle like
            $likesCount = $this->reviewService->toggleLike($review_id, $user_id);

            return response()->json([
                'message' => 'Like status toggled',
                'likes_count' => $likesCount,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in toggleLike: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
