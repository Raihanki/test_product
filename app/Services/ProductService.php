<?php

namespace App\Services;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductService
{
    public function createProduct(CreateProductRequest $createProductRequest, User $user): ProductResource;

    public function getAllProduct(?int $limit): array|LengthAwarePaginator;

    public function getProductById(Product $product): ProductResource;

    public function updateProduct(UpdateProductRequest $updateProductRequest, Product $product, User $user): ProductResource;

    public function deleteProduct(Product $product, User $user): void;

    public function getMyProduct(User $user, ?int $limit): array|LengthAwarePaginator;
}
