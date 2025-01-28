<?php

namespace App\Http\Controllers\Api;


use App\Enum\StatusEnum;
use App\Http\Requests\Api\Products\StoreProductRequest;
use App\Http\Resources\Products\ProductResource;
use App\Models\Product;
use App\Services\Api\ProductService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductsController extends ApiResponseController
{
    public function __construct()
    {
        parent::__construct(new ProductService());
    }

    public function index()
    {
        $data['products'] = $this->service->getAllProducts();

        return $this->successResponse($data);
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->service->createProduct($request);
            $data['product'] = new ProductResource($product);
        } catch (\Exception $exception) {
            return $this->serverError($exception->getMessage(), $exception->getCode());
        }
        return $this->successResponse($data, 'Product created successfully');
    }

    public function show($id)
    {
        try {
            $data['product'] = $this->service->getProductById($id);
        } catch (NotFoundHttpException $exception) {
            return $this->notFoundError($exception->getMessage());
        } catch (\Exception $exception) {
            return $this->serverError($exception->getMessage(), $exception->getCode());
        }
        return $this->successResponse($data);
    }

    public function update(StoreProductRequest $request, $id)
    {
        try {
            $product = $this->service->updateProduct($request, $id);
            $data['product'] = new ProductResource($product);
        } catch (NotFoundHttpException $exception) {
            return $this->notFoundError($exception->getMessage());
        } catch (\Exception $exception) {
            return $this->serverError($exception->getMessage(), $exception->getCode());
        }
        return $this->successResponse($data, 'Product updated successfully');
    }

    public function destroy($id)
    {
        try {
            $this->service->deleteProduct($id);
        } catch (NotFoundHttpException $exception) {
            return $this->notFoundError($exception->getMessage());
        } catch (\Exception $exception) {
            return $this->serverError($exception->getMessage(), $exception->getCode());
        }
        return $this->successResponse([], 'Product deleted successfully');
    }

}
