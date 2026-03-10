<?php

namespace App\Http\Controllers\Api;

use App\Actions\Property\GetPropertiesAction;
use App\DTO\PropertyFilterDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyCollection;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct(
        private readonly GetPropertiesAction $getPropertiesAction,
    ) {}

    public function index(Request $request): PropertyCollection
    {
        $dto        = PropertyFilterDTO::fromRequest($request->all());
        $properties = $this->getPropertiesAction->execute($dto);

        return new PropertyCollection($properties);
    }
}
