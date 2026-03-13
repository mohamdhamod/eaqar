<?php

namespace App\Http\Controllers\Api;

use App\DTO\PropertyImageDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyImageRequest;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Services\PropertyImageService;
use Illuminate\Http\JsonResponse;

class PropertyImageController extends Controller
{
    public function __construct(
        private readonly PropertyImageService $service,
    ) {
        $this->middleware('auth:sanctum');
    }

    public function index(Property $property): JsonResponse
    {
        $images = $this->service->getByProperty($property);

        return response()->json([
            'success' => true,
            'data'    => $images->map(fn(PropertyImage $img) => [
                'id'        => $img->id,
                'path'      => $img->image_path,
                'is_main'   => $img->is_main,
                'sort_order' => $img->sort_order,
            ]),
        ]);
    }

    public function store(PropertyImageRequest $request, Property $property): JsonResponse
    {
        $this->authorize('update', $property);

        $dto = PropertyImageDTO::fromRequest($request->validated(), $property->id);
        $images = $this->service->store($dto);

        return response()->json([
            'success' => true,
            'data'    => $images->map(fn(PropertyImage $img) => [
                'id'      => $img->id,
                'path'    => $img->image_path,
                'is_main' => $img->is_main,
            ]),
            'message' => __('translation.property.images_uploaded_successfully'),
        ], 201);
    }

    public function setMain(Property $property, PropertyImage $image): JsonResponse
    {
        $this->authorize('update', $property);

        $result = $this->service->setMain($property, $image);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function destroy(Property $property, PropertyImage $image): JsonResponse
    {
        $this->authorize('update', $property);

        $result = $this->service->delete($property, $image);

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}
