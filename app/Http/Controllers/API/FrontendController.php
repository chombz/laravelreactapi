<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;



class FrontendController extends Controller
{

    // Get all categories
    public function categories()
    {
        $categories = Category::where('status', '0')->get();
        return response()->json([
            'status' => 200,
            'categories' => $categories,
        ]);
    }


    // Get products by categories
    public function products($category_slug)
    {
        $category = Category::where('slug', $category_slug)->where('status', '0')->first();

        if ($category) {

            if ($category) {
                $products = Product::with('category')->where('category_id', $category->id)->where('status', '0')->get();
                return response()->json([
                    'status' => 200,
                    'products' => $products,
                    'category' => $category,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Category Found.',
                ]);
            }
        }
    }


    // Get product details
    public function viewproduct($category_slug, $product_slug)
    {
        $category = Category::where('slug', $category_slug)->where('status', '0')->first();

        if ($category) {
            $product = Product::where('category_id', $category->id)
                ->where('slug', $product_slug)
                ->where('status', '0')
                ->first();

            if ($product) {
                return response()->json([
                    'status' => 200,
                    'product'  => $product,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Product Available.',
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Such Category Found.',
            ]);
        }
    }
}
