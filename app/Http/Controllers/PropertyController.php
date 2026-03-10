<?php

namespace App\Http\Controllers;

use App\Actions\Property\GetPropertyBySlugAction;
use App\DTO\PropertyFilterDTO;
use App\Services\PropertyService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function __construct(
        private readonly PropertyService         $propertyService,
        private readonly GetPropertyBySlugAction $getPropertyBySlugAction,
    ) {}

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
}
