<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;

class JobListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JobListing::with('company');

        // Check if there is a search term
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;

            // Adjust the fields you want to search in
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'location' => 'required|string|max:255',
            'salary' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
        ]);

        $jobListing = JobListing::create($validated);

        return response()->json($jobListing, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(JobListing $jobListing)
    {
        return $jobListing->load('company');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobListing $jobListing)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'type' => 'string|max:255',
            'description' => 'string',
            'responsibilities' => 'nullable|string',
            'location' => 'string|max:255',
            'salary' => 'string|max:255',
            'company_id' => 'exists:companies,id',
        ]);

        $jobListing->update($validated);

        return response()->json($jobListing);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobListing $jobListing)
    {
        $jobListing->delete();

        return response()->json(null, 204);
    }
}
