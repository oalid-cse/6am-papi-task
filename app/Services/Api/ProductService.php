<?php

namespace App\Services\Api;

use App\Enum\StatusEnum;
use App\Http\Resources\Products\ProductCollection;
use App\Http\Resources\Products\ProductResource;
use App\Models\Product;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductService extends BaseService
{
    public function getAllProducts()
    {
        $products = Product::where('status', StatusEnum::Active->value)
            ->paginate(10);
        return $this->getPaginatedResource($products,ProductCollection::collection($products));
    }

    public function createProduct(Request $request)
    {
        //check unique product name
        $checkProduct = Product::where('name', $request->name)
            ->where('status', StatusEnum::Active->value)
            ->first();
        if (!empty($checkProduct)) {
            throw new \Exception('Product name already exists', 422);
        }
        $product = new Product();
        $this->setProductInfo($request, $product);

        $product->status = StatusEnum::Active->value;
        $product->created_by = auth()->id();
        $product->save();

        return $product;
    }

    public function getProductById($id)
    {
        $product = Product::where('id', $id)
            ->where('status', StatusEnum::Active->value)
            ->first();
        if (empty($product)) {
            throw new NotFoundHttpException('Product not found');
        }
        return new ProductResource($product);
    }

    public function updateProduct(Request $request, $id)
    {
        //check unique product name
        $checkProduct = Product::where('name', $request->name)
            ->where('id', '!=', $id)
            ->where('status', StatusEnum::Active->value)
            ->first();
        if (!empty($checkProduct)) {
            throw new \Exception('Product name already exists', 422);
        }
        $product = Product::where('id', $id)
            ->where('status', StatusEnum::Active->value)
            ->first();
        if (empty($product)) {
            throw new NotFoundHttpException('Product not found');
        }
        $this->setProductInfo($request, $product);

        $product->updated_by = auth()->id();
        $product->save();

        return $product;
    }

    public function deleteProduct($id)
    {
        $product = Product::where('id', $id)
            ->where('status', StatusEnum::Active->value)
            ->first();
        if (empty($product)) {
            throw new NotFoundHttpException('Product not found');
        }
        $product->delete();
    }

    /**
     * @param Request $request
     * @param $product
     * @return void
     */
    public function setProductInfo(Request $request, $product): void
    {
        $product->name = $request->name;
        $product->description = $request->description ?? null;
        $product->price = $request->price;
        $product->stock = $request->stock;

        if ($request->hasFile('image')) {
            $upload = FileUploadService::uploadFile($request->image, 'products');
            $product->image = $upload['path'];
        }
    }
}
