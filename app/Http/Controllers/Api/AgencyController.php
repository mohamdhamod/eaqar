<?php

namespace App\Http\Controllers\Api;

use App\Actions\GetAgenciesAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\AgencyCollection;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function __construct(
        private readonly GetAgenciesAction $getAgenciesAction,
    ) {}

    public function index(Request $request): AgencyCollection
    {
        $search  = $request->input('search');
        $perPage = min((int) ($request->input('per_page', 20)), 50);

        $agencies = $this->getAgenciesAction->handle(
            perPage: $perPage,
            search: $search,
        );

        return new AgencyCollection($agencies);
    }
}
