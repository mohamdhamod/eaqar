<?php

namespace App\Http\Controllers;

use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function __construct(
        private readonly PropertyService $propertyService,
    ) {}

    /**
     * Show the application homepage with filter options.
     */
    public function index(Request $request)
    {
        try {
            $filterOptions = $this->propertyService->getFilterOptions();

            return view('home.index', compact('filterOptions'));
        } catch (\Exception $e) {
            Log::error('HomeController index error: ' . $e->getMessage());

            return view('home.index', [
                'filterOptions' => ['cities' => collect([]), 'operation_types' => collect([]), 'property_types' => collect([])],
            ]);
        }
    }

    /**
     * Check user login status (used by front-end session checks)
     */
    public function checkLoginStatus(): JsonResponse
    {
        return response()->json([
            'authenticated' => auth()->check(),
            'login_url'     => route('login'),
            'register_url'  => route('register'),
        ]);
    }
}

