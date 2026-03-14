<?php

namespace App\Http\Controllers;

use App\DTO\AgencyDTO;
use App\Http\Requests\AgencyRequest;
use App\Http\Resources\AgencyResource;
use App\Models\Agency;
use App\Services\AgencyService;
use App\Traits\FileHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AgencyController extends Controller
{
    use FileHandler;

    public function __construct(
        private readonly AgencyService $agencyService,
    ) {}

    /**
     * Show properties dashboard (list user's properties).
     */
    public function index(): View
    {
        $agency = $this->agencyService->getUserAgency(auth()->id());

        if (!$agency) {
            // No agency yet, show form
            return view('agency.form', ['agency' => null]);
        }

        // Load properties for this agency, sorted by latest first
        $properties = $agency->properties()
            ->orderByDesc('created_at')
            ->with('propertyType', 'images')
            ->get();

        return view('agency.dashboard', [
            'agency' => $agency,
            'properties' => $properties,
        ]);
    }

    /**
     * Show create/edit agency form (unified form).
     */
    public function show(): View
    {
        $agency = $this->agencyService->getUserAgency(auth()->id());

        return view('agency.form', [
            'agency' => $agency,
        ]);
    }

    /**
     * Store a newly created agency.
     */
    public function store(AgencyRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->storeFile($request->file('logo'), 'agencies');
        }

        $dto = AgencyDTO::fromArray($validated);
        $this->agencyService->registerAgent(auth()->id(), $dto);

        return redirect()->route('agency.index')
            ->with('success', __('translation.agency.registered_successfully'));
    }

    /**
     * Update the specified agency.
     */
    public function update(AgencyRequest $request): RedirectResponse
    {
        $agency = $this->agencyService->getUserAgency(auth()->id());

        if (!$agency) {
            abort(404);
        }

        $validated = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->updateFile($request->file('logo'), $agency->logo, 'agencies');
        }

        $dto = AgencyDTO::fromArray($validated);
        $this->agencyService->updateAgency($agency->id, $dto);

        return redirect()->route('agency.index')
            ->with('success', __('translation.agency.updated_successfully'));
    }

    /**
     * Show agency data (for API).
     */
    public function showApi(): AgencyResource
    {
        $agency = $this->agencyService->getUserAgency(auth()->id());

        if (!$agency) {
            abort(404);
        }

        return new AgencyResource($agency);
    }
}

