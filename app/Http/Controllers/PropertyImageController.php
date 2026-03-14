<?php

namespace App\Http\Controllers;

use App\DTO\PropertyImageDTO;
use App\Enums\PermissionEnum;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Http\Requests\PropertyImageRequest;
use App\Services\PropertyImageService;
use Illuminate\Http\JsonResponse;

class PropertyImageController extends Controller
{
    public function __construct(
        private readonly PropertyImageService $service,
    ) {
        $this->middleware('auth');
        $this->middleware('permission:' . PermissionEnum::MANAGE_PROPERTIES_ADD)->only(['store']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_PROPERTIES_UPDATE)->only(['makeMain']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_PROPERTIES_DELETE)->only(['destroy']);
        $this->middleware('property.subscription')->only(['store']);
    }

    public function store(PropertyImageRequest $request, $lang, Property $property): JsonResponse
    {
        $this->authorize('update', $property);

        $dto = PropertyImageDTO::fromRequest($request->validated(), $property->id);
        $images = $this->service->store($dto);

        return response()->json([
            'success' => true,
            'images'  => $images->map(fn(PropertyImage $img) => [
                'id'      => $img->id,
                'path'    => $img->image_path,
                'is_main' => $img->is_main,
            ]),
            'message' => __('translation.property.images_uploaded_successfully'),
        ], 201);
    }

    public function makeMain($lang, Property $property, PropertyImage $image): JsonResponse
    {
        $this->authorize('update', $property);

        $result = $this->service->setMain($property, $image);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function destroy($lang, Property $property, PropertyImage $image): JsonResponse
    {
        $this->authorize('update', $property);

        $result = $this->service->delete($property, $image);

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}
