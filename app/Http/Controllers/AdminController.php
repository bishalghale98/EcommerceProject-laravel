<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }


    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function add_brand()
    {
        return view('admin.brand-add');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:jpeg,png,jpg|max:2048',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;

            // Generate and save thumbnail image
            $this->GenerateBrandThumbnailImage($image, $file_name);
            $brand->image = $file_name; // Save image name in database
        }


        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Brand added successfully');
    }

    public function brand_edit($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand-edit', compact('brand'));
    }

    public function brand_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $id, // Allow the current brand's slug
            'image' => 'nullable|mimes:jpeg,png,jpg|max:2048', // Make image optional
        ]);

        // Find the brand by ID
        $brand = Brand::findOrFail($id);

        // Update the brand name and slug
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Check if the old image exists and delete it
            if (File::exists(public_path('uploads/brands') . '/' . $brand->image)) {
                File::delete(public_path('uploads/brands') . '/' . $brand->image); // Delete the old image
            }

            // Upload the new image
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;

            // Generate and save the thumbnail image
            $this->GenerateBrandThumbnailImage($image, $file_name);
            $brand->image = $file_name; // Save the new image name in the database
        }

        // Save the updated brand
        $brand->update();

        // Redirect back with a success message
        return redirect()->route('admin.brands')->with('status', 'Brand updated successfully');
    }

    public function GenerateBrandThumbnailImage($image, $imageName)
    {
        // Define destination path for image upload
        $destinationPath = public_path('uploads/brands');

        // Use Intervention Image to manipulate image
        $img = Image::read($image->path()); // Open the uploaded image

        // Resize and crop image to 124x124 while maintaining aspect ratio
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($destinationPath . '/' . $imageName); // Save the image with the new name
    }


    public function brand_delete($id)
    {
        $brand = Brand::findOrFail($id);
        if (File::exists(public_path('uploads/brands') . '/' . $brand->image)) {
            File::delete(public_path('uploads/brands') . '/' . $brand->image);
        }
        $brand->delete();

        return redirect()->route('admin.brands')->with('status', 'Brand has been deleted successfully');
    }



}
