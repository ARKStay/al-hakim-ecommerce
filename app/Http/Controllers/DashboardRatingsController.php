<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DashboardRatingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ratings = Rating::with(['user', 'product'])
            ->filter($request->only('search'))
            ->latest()
            ->get();

        return view('dashboard.ratings.index', [
            'title' => 'Rating List',
            'ratings' => $ratings,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rating = Rating::findOrFail($id);
        $rating->delete();

        return redirect()->route('ratings.index')->with('success', 'Rating deleted successfully!');
    }
}
