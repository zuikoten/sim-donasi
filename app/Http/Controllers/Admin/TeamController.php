<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::orderBy('order', 'asc')->get();

        // Check if request is AJAX/JSON
        if (request()->wantsJson()) {
            return response()->json($teams);
        }

        // Return view with teams data for server-side rendering
        return view('admin.teams.index', compact('teams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'position', 'description']);

        // Upload photo
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->imageService->uploadTeamPhoto($request->file('photo'));
        }

        // Set order as last
        $data['order'] = Team::max('order') + 1;
        $data['is_active'] = $request->input('is_active', 1);

        $team = Team::create($data);

        return redirect()->back()->with('success', 'Team member added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        return response()->json($team);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        return view('admin.teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'position', 'description']);

        if ($request->has('is_active')) {
            $data['is_active'] = $request->is_active;
        }

        // Upload new photo if provided
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->imageService->uploadTeamPhoto($request->file('photo'), $team->photo);
        }

        $team->update($data);

        return redirect()->back()->with('success', 'Team member updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        // Delete photo if exists
        if ($team->photo) {
            $this->imageService->deleteImage($team->photo);
        }

        $team->delete();

        return redirect()->back()->with('success', 'Team member deleted successfully!');
    }

    /**
     * Move team member up or down
     */
    public function move(Request $request, Team $team)
    {
        $direction = $request->input('direction'); // 'up' or 'down'

        if ($direction === 'up') {
            $sibling = Team::where('order', '<', $team->order)
                ->orderBy('order', 'desc')
                ->first();
        } else {
            $sibling = Team::where('order', '>', $team->order)
                ->orderBy('order', 'asc')
                ->first();
        }

        if ($sibling) {
            // Swap order
            $tempOrder = $team->order;
            $team->order = $sibling->order;
            $sibling->order = $tempOrder;

            $team->save();
            $sibling->save();
        }

        return redirect()->back()->with('success', 'Order updated successfully!');
    }
}
