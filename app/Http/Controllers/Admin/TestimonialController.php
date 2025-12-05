<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class TestimonialController extends Controller
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
        $testimonials = Testimonial::orderBy('order', 'asc')->get();

        // Check if request is AJAX/JSON
        if (request()->wantsJson()) {
            return response()->json($testimonials);
        }

        // Return view with testimonials data for server-side rendering
        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $data = $request->only(['name', 'role', 'rating', 'comment']);

        // Upload photo
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->imageService->uploadTestimonialPhoto($request->file('photo'));
        }

        // Set order as last
        $data['order'] = Testimonial::max('order') + 1;
        $data['is_active'] = $request->input('is_active', 1);

        $testimonial = Testimonial::create($data);

        return redirect()->back()->with('success', 'Testimonial added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $testimonial)
    {
        return response()->json($testimonial);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'role', 'rating', 'comment']);

        if ($request->has('is_active')) {
            $data['is_active'] = $request->is_active;
        }

        // Upload new photo if provided
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->imageService->uploadTestimonialPhoto($request->file('photo'), $testimonial->photo);
        }

        $testimonial->update($data);

        return redirect()->back()->with('success', 'Testimonial updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        // Delete photo if exists
        if ($testimonial->photo) {
            $this->imageService->deleteImage($testimonial->photo);
        }

        $testimonial->delete();

        return redirect()->back()->with('success', 'Testimonial deleted successfully!');
    }

    /**
     * Move testimonial up or down
     */
    public function move(Request $request, Testimonial $testimonial)
    {
        $direction = $request->input('direction'); // 'up' or 'down'

        if ($direction === 'up') {
            $sibling = Testimonial::where('order', '<', $testimonial->order)
                ->orderBy('order', 'desc')
                ->first();
        } else {
            $sibling = Testimonial::where('order', '>', $testimonial->order)
                ->orderBy('order', 'asc')
                ->first();
        }

        if ($sibling) {
            // Swap order
            $tempOrder = $testimonial->order;
            $testimonial->order = $sibling->order;
            $sibling->order = $tempOrder;

            $testimonial->save();
            $sibling->save();
        }

        return redirect()->back()->with('success', 'Order updated successfully!');
    }
}
