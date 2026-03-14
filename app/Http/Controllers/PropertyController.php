<?php

namespace App\Http\Controllers;

use App\Actions\Property\GetPropertyBySlugAction;
use App\DTO\PropertyCreateDTO;
use App\DTO\PropertyFilterDTO;
use App\Enums\PermissionEnum;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Property;
use App\Services\PropertyService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function __construct(
        private readonly PropertyService         $propertyService,
        private readonly GetPropertyBySlugAction $getPropertyBySlugAction,
    ) {
        $this->middleware('permission:' . PermissionEnum::MANAGE_PROPERTIES_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_PROPERTIES_UPDATE)->only(['edit', 'update']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_PROPERTIES_DELETE)->only(['destroy']);
        $this->middleware('property.subscription')->only(['create', 'store']);
    }

    public function index(Request $request): View
    {
        $filterOptions = $this->propertyService->getFilterOptions();
        $activeFilters = PropertyFilterDTO::fromRequest($request->all());

        return view('properties.index', compact('filterOptions', 'activeFilters'));
    }

    public function show($lang , string $slug): View
    {
        $property = $this->getPropertyBySlugAction->execute($slug);
        
        abort_if($property === null, 404);
        
        $relatedProperties = $this->propertyService->getRelatedProperties($property);
        
        return view('properties.show', compact('property', 'relatedProperties'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create(): View
    {
        $filterOptions = $this->propertyService->getFilterOptions();
        $userAgency    = auth()->user()->agency;

        return view('properties.create', compact('filterOptions', 'userAgency'));
    }

    /**
     * Store a newly created property in storage.
     */
    public function store(StorePropertyRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Handle images from request
        $images = [];
        if ($request->hasFile('images')) {
            $images = $request->file('images');
        }
        $validated['images'] = $images;
        $validated['main_image_index'] = (int) ($validated['main_image_index'] ?? 0);

        $dto = PropertyCreateDTO::fromRequest($validated);
        $property = $this->propertyService->createProperty($dto, $request->user()->id);

        return redirect()
            ->route('properties.show', $property->slug)
            ->with('success', __('translation.property.created_successfully'));
    }

    /**
     * Show the form for editing a property.
     */
    public function edit($lang , Property $property): View
    {
       
        $this->authorize('update', $property);

        $filterOptions = $this->propertyService->getFilterOptions();
        $currentImages = $property->images()->get();
        $userAgency = auth()->user()->agency;

        return view('properties.edit', compact('property', 'filterOptions', 'currentImages', 'userAgency'));
    }

    /**
     * Update the specified property.
     */
    public function update( UpdatePropertyRequest $request, $lang , Property $property): RedirectResponse
    {
        $this->authorize('update', $property);

        $validated = $request->validated();

        // Handle images from request
        $images = [];
        if ($request->hasFile('images')) {
            $images = $request->file('images');
        }
        $validated['images'] = $images;
        $validated['main_image_index'] = (int) ($validated['main_image_index'] ?? 0);

        $dto = PropertyCreateDTO::fromRequest($validated);
        $property = $this->propertyService->updateProperty($property, $dto);

        return redirect()
            ->route('properties.show', $property->slug)
            ->with('success', __('translation.property.updated_successfully'));
    }

    /**
     * Delete the specified property.
     */
    public function destroy($lang , Property $property): RedirectResponse
    {
        $this->authorize('delete', $property);

        $this->propertyService->deleteProperty($property);

        return response()->json([
            'success' => true,
            'redirect' => route('agency.index'),
            'message' => __('translation.messages.deleted_successfully')
        ], 200);

    }
}
