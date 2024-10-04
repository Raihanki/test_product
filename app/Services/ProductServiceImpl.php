<?php

namespace App\Services;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;

class ProductServiceImpl implements ProductService
{
    public function createProduct(CreateProductRequest $createProductRequest, User $user): ProductResource
    {
        $data = $createProductRequest->validated();
        if ($createProductRequest->hasFile('image')) {
            $data['image'] = Storage::disk('public')->put('products/images', $createProductRequest->file('image'));
        }
        return new ProductResource($user->products()->create($data));
    }

    public function getAllProduct(?int $limit): LengthAwarePaginator
    {
        return Product::paginate($limt ?? 10);
    }

    public function getProductById(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    public function updateProduct(UpdateProductRequest $updateProductRequest, Product $product, User $user): ProductResource
    {
        $data = $updateProductRequest->validated();
        if ($user->id !== $product->user_id) {
            throw new UnauthorizedException('Access denied');
        }

        if ($updateProductRequest->hasFile('image')) {
            Storage::disk('public')->delete($product->image);
            $data['image'] = Storage::disk('public')->put('products/images', $updateProductRequest->file('image'));
        } else {
            $data['image'] = $product->image;
        }

        $product->update($data);
        return new ProductResource($product);
    }

    public function deleteProduct(Product $product, User $user): void
    {
        if ($user->id !== $product->user_id) {
            throw new UnauthorizedException('Access denied');
        }

        $product->delete();
    }

    public function getMyProduct(User $user, ?int $limit): LengthAwarePaginator
    {
        return Product::where('user_id', $user->id)->paginate($limit ?? 10);
    }
}
