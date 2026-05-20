<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeSlideController extends Controller
{
    public function index()
    {
        $slides = HomeSlide::orderBy('order')->get();
        return view('admin.home-slides.index', compact('slides'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'desc' => 'required|string',
            'image' => 'required|image|max:4096',
            'order' => 'integer|nullable',
        ]);

        $data = $request->only(['title', 'desc', 'order']);
        $data['order'] = $data['order'] ?? (HomeSlide::max('order') + 1);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('assets', 'public');
            $data['image'] = 'storage/' . $path;
        }

        HomeSlide::create($data);

        return redirect()->route('admin.home-slides.index')->with('success', 'Slide berhasil ditambahkan!');
    }

    public function update(Request $request, HomeSlide $homeSlide)
    {
        $request->validate([
            'title' => 'required|string',
            'desc' => 'required|string',
            'image' => 'nullable|image|max:4096',
            'order' => 'integer|nullable',
        ]);

        $data = $request->only(['title', 'desc', 'order']);
        
        if ($request->hasFile('image')) {
            // Delete old image if it is an uploaded file
            if (str_starts_with($homeSlide->image, 'storage/')) {
                $oldPath = str_replace('storage/', '', $homeSlide->image);
                Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('image')->store('assets', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $homeSlide->update($data);

        return redirect()->route('admin.home-slides.index')->with('success', 'Slide berhasil diperbarui!');
    }

    public function destroy(HomeSlide $homeSlide)
    {
        if (str_starts_with($homeSlide->image, 'storage/')) {
            $oldPath = str_replace('storage/', '', $homeSlide->image);
            Storage::disk('public')->delete($oldPath);
        }

        $homeSlide->delete();

        return redirect()->route('admin.home-slides.index')->with('success', 'Slide berhasil dihapus!');
    }
}
