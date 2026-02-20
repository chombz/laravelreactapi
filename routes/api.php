<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\FrontendController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\OrderController;



// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public route to get categories
Route::get('getCategories', [FrontendController::class, 'categories']); // Get all categories
Route::get('fetchProducts/{category_slug}', [FrontendController::class, 'products']); // Get products by categories
Route::get('view-product-detail/{category_slug}/{product_slug}', [FrontendController::class, 'viewproduct']); // Get product details

//Orders
Route::get('admin/orders', [OrderController::class, 'index']); //
Route::get('admin/view-order/{id}', [OrderController::class, 'show']);

// Cart routes
Route::post('add-to-cart', [CartController::class, 'addToCart']); // Add to cart
Route::get('cart', [CartController::class, 'viewCart']); // View cart items
Route::put('cart-updatequantity/{cart_id}/{scope}', [CartController::class, 'updateCartQuantity']); // Update cart item quantity

Route::delete('delete-cartitem/{cart_id}', [CartController::class, 'deleteCartItem']); // Delete cart item

// Checkout route
Route::post('validate-order', [CheckoutController::class, 'validateOrder']); // Validate order details
Route::post('place-order', [CheckoutController::class, 'placeOrder']); // Place order


// Routes for authenticated users
Route::middleware('auth:sanctum')->group(function () {
    // Any authenticated user should be able to log out
    Route::post('/logout', [AuthController::class, 'logout']);

    // This endpoint returns the authenticated user's details
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    // Category Routes
    // Using 'categories' follows RESTful conventions and is more descriptive.
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::get('edit-category/{id}', [CategoryController::class, 'edit']);
    Route::put('update-category/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
    // Public route to get all categories
    Route::get('all-category', [CategoryController::class, 'allcategory']);

    //Product Routes
    Route::post('store-product', [ProductController::class, 'store']); //Add product
    Route::get('edit-product/{id}', [ProductController::class, 'edit']); //Edit product
    Route::get('view-products', [ProductController::class, 'index']); //View products
    Route::post('update-product/{id}', [ProductController::class, 'update']); //Update product

});

// Admin-specific routes can be grouped here if needed
Route::middleware(['auth:sanctum', 'isAPIAdmin'])->group(function () {
    // Example: Route::get('/admin/stats', [AdminController::class, 'getStats']);
});
