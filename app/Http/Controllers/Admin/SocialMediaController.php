<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $socialMedia = SocialMedia::orderBy('order', 'asc')->get();

        // Check if request is AJAX/JSON
        if (request()->wantsJson()) {
            return response()->json($socialMedia);
        }

        // Return view with social media data for server-side rendering
        return view('admin.social-media.index', compact('socialMedia'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'platform_name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'icon_class' => 'required|string|max:100',
        ]);

        $data = $request->only(['platform_name', 'url', 'icon_class']);

        // Set order as last
        $data['order'] = SocialMedia::max('order') + 1;
        $data['is_active'] = $request->input('is_active', 1);

        $socialMedia = SocialMedia::create($data);

        return redirect()->back()->with('success', 'Social media account added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialMedia $socialMedia)
    {
        return response()->json($socialMedia);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocialMedia $socialMedia)
    {
        $request->validate([
            'platform_name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'icon_class' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['platform_name', 'url', 'icon_class']);

        if ($request->has('is_active')) {
            $data['is_active'] = $request->is_active;
        }

        $socialMedia->update($data);

        return redirect()->back()->with('success', 'Social media account updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialMedia $socialMedia)
    {
        $socialMedia->delete();

        return redirect()->back()->with('success', 'Social media account deleted successfully!');
    }

    /**
     * Move social media up or down
     */
    public function move(Request $request, SocialMedia $socialMedia)
    {
        $direction = $request->input('direction'); // 'up' or 'down'

        if ($direction === 'up') {
            $sibling = SocialMedia::where('order', '<', $socialMedia->order)
                ->orderBy('order', 'desc')
                ->first();
        } else {
            $sibling = SocialMedia::where('order', '>', $socialMedia->order)
                ->orderBy('order', 'asc')
                ->first();
        }

        if ($sibling) {
            // Swap order
            $tempOrder = $socialMedia->order;
            $socialMedia->order = $sibling->order;
            $sibling->order = $tempOrder;

            $socialMedia->save();
            $sibling->save();
        }

        return redirect()->back()->with('success', 'Order updated successfully!');
    }
}
