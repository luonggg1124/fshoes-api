<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\Discount\SaleController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\GeminiController;
use App\Http\Controllers\Api\GroupsController;
use App\Http\Controllers\Api\Image\ImageController;
use App\Http\Controllers\Api\OrderDetailsController;
use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\PaymentOnline;
use App\Http\Controllers\Api\PostsController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Product\Variation\VariationController;
use App\Http\Controllers\Api\Review\ReviewController;
use App\Http\Controllers\Api\TopicsController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\VouchersController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Sabberworm\CSS\Property\Import;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');



Route::get('auth/unauthorized',function(){
    return response()->json([
        'status' => false,
        'message' => 'Unauthorized!',
    ],401);
})->name('login');


//Admin
Route::group(['middleware' => ['auth:api','is_admin']], function(){

});

// End Admin
Route::apiResource('category',CategoryController::class)->parameter('category','id')->except(['index','show']);
Route::get('user',[UserController::class,'index']);
Route::get('count/user/has/orders',[UserController::class,'userHasOrderCount']);

// Auth
Route::group(['middleware' => ['auth:api']],function(){
    Route::apiResource('product',ProductController::class)->parameter('product','id')->except('index','show');
    Route::get('product/with/trashed',[ProductController::class,'productWithTrashed'])->name('product.with.trashed');
    Route::get('product/trashed',[ProductController::class,'productTrashed'])->name('product.list.trashed');
    Route::get('product/trashed/{id}',[ProductController::class,'getOneTrashed'])->name('product.one.trashed');
    Route::post('product/restore/{id}',[ProductController::class,'restore'])->name('product.restore');
    Route::delete('product/force-delete/{id}',[ProductController::class,'forceDestroy'])->name('product.force.delete');
    Route::put('status/product/{id}',[ProductController::class,'updateProductStatus'])->name('product.update.status');
    Route::apiResource('product.variation',VariationController::class)->parameters(['product' => 'pid', 'variation'=>'id']);

    Route::apiResource('user',UserController::class)->parameter('user','id')->except('update');
    Route::post('user/{id}',[UserController::class,'update'])->name('user.update');


    Route::put('update-profile',[UserController::class,'updateProfile'])->name('update-profile');
    Route::post('change-password',[\App\Http\Controllers\Api\User\AuthController::class,'changePassword'])->name('user.changePassword');
    Route::post('logout',[\App\Http\Controllers\Api\User\AuthController::class,'logout']);
    Route::get('auth/me',[\App\Http\Controllers\Api\User\AuthController::class,'me']);

    Route::get('user/get-favorite/product',[UserController::class,'getFavoriteProduct'])->name('get.favorite.product');
    Route::post('user/add-favorite/product/{product_id}',[UserController::class,'addFavoriteProduct'])->name('add.favorite.product');
    Route::delete('user/remove-favorite/product/{product_id}',[UserController::class,'removeFavoriteProduct'])->name('remove.favorite.product');


    Route::post('review/{id}/like', [ReviewController::class, 'toggleLike'])->name('review.like');
    Route::apiResource('review',ReviewController::class)->parameter('review','id');


    Route::get('me/orders' , [OrdersController::class , 'me']);
    Route::patch('cancel/order/{id}' , [OrdersController::class ,'cancelOrder']);
    Route::post('reorder/order/{id}' , [OrdersController::class ,'reOrder']);
    Route::put('order/update/payment-status/{id}',[OrdersController::class,'updatePaymentStatus']);

//Discount
    Route::put('sale/switch/active/{id}' , [SaleController::class ,'switchActive']);
    Route::apiResource('sale',SaleController::class)->parameters(['sale' => 'id'])->except('index');
//Discount End
//Image
    Route::post('update/user/avatar',[UserController::class,'updateAvatar']);
    Route::apiResource('image',ImageController::class)->parameter('image','id')->only(['index','store','destroy']);
    Route::delete('image/delete-many',[ImageController::class,'deleteMany'])->name('image.delete.many');
//End Image

    Route::post('category/{id}/products',[CategoryController::class,'addProducts'])->name('category.add.products');
    Route::delete('category/{id}/products',[CategoryController::class,'deleteProducts'])->name('category.delete.products');

    // Attribute - Attribute Value Start
    Route::apiResource('attribute',\App\Http\Controllers\Api\Attribute\AttributeController::class)->parameter('attribute','id');
    Route::get('get/attribute/values/product/{id}',[ProductController::class,'getAttributeValues'])->name('get.attribute.values');
    Route::post('add/attribute/values/product/{id}',[ProductController::class,'createAttributeValues'])->name('add.attribute.values');
    Route::apiResource('attribute.value',\App\Http\Controllers\Api\Attribute\Value\AttributeValueController::class)->parameters(['attribute'=>'aid','value' => 'id'])->except('update');
//Attribute - Attribute Value End
    

Route::get('vouchers/code/{code}' , [VouchersController::class,'getVoucherByCode']);
});

Route::post('auth/refresh/token',[\App\Http\Controllers\Api\User\AuthController::class,'refresh']);
Route::post('/check/email',[\App\Http\Controllers\Api\User\AuthController::class,'checkEmail']);
Route::post('login',[\App\Http\Controllers\Api\User\AuthController::class,'login']);
Route::post('register',[\App\Http\Controllers\Api\User\AuthController::class,'register']);
Route::get('attributes/isFilter',[\App\Http\Controllers\Api\Attribute\AttributeController::class,'isFilterAttributes']);
// End Auth

// Category Start

Route::apiResource('category', CategoryController::class)
    ->parameter('category', 'id')->only(['index','show']);
Route::get('main/categories',[CategoryController::class,'mains'])->name('main.categories');

// Category End
Route::apiResource('sale',SaleController::class)->parameters(['sale' => 'id'])->only('index');
Route::get('sales/stream',[SaleController::class,'stream'])->name('sale.stream');

//Cart
Route::apiResource('cart' , CartController::class);

//Order
Route::apiResource('orders' , OrdersController::class);




//Order Detail
Route::apiResource('order-details' , OrderDetailsController::class);

//Payment
Route::post('momo' , [PaymentOnline::class , 'momo']);
Route::post('vnpay' , [PaymentOnline::class , 'vnpay']);
Route::post('stripe' , [PaymentOnline::class , 'stripe']);
Route::post('paypal' , [PaymentOnline::class , 'paypal']);
        Route::get('success-paypal' , [PaymentOnline::class , 'successPaypal'])->name('successPaypal');
        Route::get('error-paypal' , [PaymentOnline::class , 'errorPaypal'])->name('errorPaypal');

//Product Start
Route::get('trend/this-week/products',[ProductController::class,'thisWeekProducts'])->name('this.week.products');
Route::get('best-selling/products',[ProductController::class,'bestSellingProducts'])->name('best.selling.products');
Route::get('shop-by-sports/products',[ProductController::class,'shopBySports'])->name('shop.by.sports');
Route::get('product/detail/{id}',[ProductController::class,'productDetail'])->name('product.detail');
Route::apiResource('product',ProductController::class)->parameter('product','id')->only('index','show');
Route::get('products/category/{categoryId}',[ProductController::class,'productsByCategory'])->name('products.category');
Route::get('product/by/attribute-values',[ProductController::class,'productsByAttributeValues'])->name('product.by.attribute.values');
Route::get('products/all/summary',[ProductController::class,'allSummary'])->name('product.all.summary');

//Product End



// Review

// Like

Route::get('product/{id}/reviews',[ReviewController::class,'reviewsByProduct'])->name('product.reviews');


// End Review



//Route::get('api/auth/google/redirect', [SocialiteController::class, 'googleRedirect']);
//Route::post('auth/google/callback', [SocialiteController::class, 'googleCallback']);

Route::get('test',[TestController::class,'test']);


//Groups
Route::apiResource('groups' , GroupsController::class);
Route::post('groups/restore/{id}' , [GroupsController::class,'restore']);
Route::delete('groups/forceDelete/{id}' , [GroupsController::class,'forceDelete']);


//Topics
Route::apiResource('topics' , TopicsController::class);
Route::post('topics/restore/{id}' , [TopicsController::class,'restore']);
Route::delete('topics/forceDelete/{id}' , [TopicsController::class,'forceDelete']);

//Posts
Route::apiResource('posts' , PostsController::class);
Route::post('posts/restore/{id}' , [PostsController::class,'restore']);
Route::delete('posts/forceDelete/{id}' , [PostsController::class,'forceDelete']);

//Vouchers
Route::apiResource('vouchers' , VouchersController::class);
Route::post('vouchers/restore/{id}' , [VouchersController::class,'restore']);

Route::delete('vouchers/forceDelete/{id}' , [VouchersController::class,'forceDelete']);

//Export Invoice
Route::get('export/order/{id}' ,[ExportController::class,'exportInvoice']);


//Export List Record
Route::post('export/order' ,[ExportController::class,'exportOrder']);
Route::post('export/user' ,[ExportController::class,'exportUser']);
Route::post('export/product' ,[ExportController::class,'exportProduct']);


//Statistics
Route::get('statistics/order', [\App\Http\Controllers\Api\StatisticsController::class , 'order']);
Route::get('statistics/product', [\App\Http\Controllers\Api\StatisticsController::class , 'product']);
Route::get('statistics/user', [\App\Http\Controllers\Api\StatisticsController::class , 'user']);
Route::get('statistics/review', [\App\Http\Controllers\Api\StatisticsController::class , 'review']);
Route::get('statistics/overall', [\App\Http\Controllers\Api\StatisticsController::class , 'overall']);


//Gemini
Route::post('/gemini/text', [GeminiController::class, 'text']);


//Import
Route::post('import/voucher' , [\App\Http\Controllers\Api\ImportVoucher::class, 'import']);
Route::fallback(function () {
    return response()->json([
        'message' => 'Route not found',
        'error' => 'Not Found',
    ], 404);
});