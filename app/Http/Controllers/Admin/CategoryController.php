<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;



class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:jpeg,png,jpg|max:2048',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;

            // Generate and save thumbnail image
            $this->GenerateCategoryThumbnailImage($image, $file_name);
            $category->image = $file_name; // Save image name in database
        }

        $category->save();

        return redirect()->route('admin.categories.index')->with('status', 'Category added successfully!');
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
        $category = Category::find($id);
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $id, // Allow the current brand's slug
            'image' => 'nullable|mimes:jpeg,png,jpg|max:2048', // Make image optional
        ]);

        // Find the category by ID
        $category = Category::findOrFail($id);

        // Update the category name and slug
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        // Handle image upload
        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/categories') . '/' . $category->image)) {
                File::delete(public_path('uploads/categories') . '/' . $category->image); // Delete the old image
            }

            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;

            $this->GenerateCategoryThumbnailImage($image, $file_name);
            $category->image = $file_name;
        }

        // Save the updated category$category
        $category->update();

        // Redirect back with a success message
        return redirect()->route('admin.categories.index')->with('status', 'Category updated successfully !');
    }

    public function GenerateCategoryThumbnailImage($image, $imageName)
    {

        $destinationPath = public_path('uploads/categories');

        $img = Image::read($image->path());

        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($destinationPath . '/' . $imageName);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        if (File::exists(public_path('uploads/categories') . '/' . $category->image)) {
            File::delete(public_path('uploads/categories') . '/' . $category->image);
        }
        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', 'Categories has been deleted successfully');
    }
}
