<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $products = $this->productService->getAllProduct($request->query('limit'));
            return response()->json([
                "status" => 200,
                "data" => $products
            ], 200);
        } catch (Exception $e) {
            Log::error("Error When Get All Product : " . $e->getMessage());
            return response()->json([
                "status" => 500,
                "data" => "Internal Server Error"
            ], 500);
        }
    }

    public function store(CreateProductRequest $createProductRequest): JsonResponse
    {
        $createProductRequest->validated();
        try {
            $product = $this->productService->createProduct($createProductRequest, $createProductRequest->user());
            return response()->json([
                "status" => 201,
                "data" => $product
            ], 201);
        } catch (Exception $e) {
            Log::error("Error When Create Product : " . $e->getMessage());
            return response()->json([
                "status" => 500,
                "data" => "Internal Server Error"
            ], 500);
        }
    }

    public function show(Product $product): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($product);
            return response()->json([
                "status" => 200,
                "data" => $product
            ], 200);
        } catch (Exception $e) {
            Log::error("Error When Get Product By Id : " . $e->getMessage());
            return response()->json([
                "status" => 500,
                "data" => "Internal Server Error"
            ], 500);
        }
    }

    public function update(UpdateProductRequest $updateProductRequest, Product $product): JsonResponse
    {
        $updateProductRequest->validated();
        try {
            $product = $this->productService->updateProduct($updateProductRequest, $product, $updateProductRequest->user());
            return response()->json([
                "status" => 200,
                "data" => $product
            ], 200);
        } catch (UnauthorizedException $e) {
            return response()->json([
                "status" => 400,
                "data" => $e->getMessage()
            ], 400);
        } catch (Exception $e) {
            Log::error("Error When Update Product : " . $e->getMessage());
            return response()->json([
                "status" => 500,
                "data" => "Internal Server Error"
            ], 500);
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            $this->productService->deleteProduct($product, request()->user());
            return response()->json([
                "status" => 200,
                "data" => "Product Deleted"
            ], 200);
        } catch (UnauthorizedException $e) {
            return response()->json([
                "status" => 400,
                "data" => $e->getMessage()
            ], 400);
        } catch (Exception $e) {
            Log::error("Error When Delete Product : " . $e->getMessage());
            return response()->json([
                "status" => 500,
                "data" => "Internal Server Error"
            ], 500);
        }
    }

    public function myProducts(Request $request): JsonResponse
    {
        try {
            $products = $this->productService->getMyProduct($request->user(), $request->query('limit'));
            return response()->json([
                "status" => 200,
                "data" => $products
            ], 200);
        } catch (Exception $e) {
            Log::error("Error When Get My Product : " . $e->getMessage());
            return response()->json([
                "status" => 500,
                "data" => "Internal Server Error"
            ], 500);
        }
    }
}
