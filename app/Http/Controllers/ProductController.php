<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // This method will show products page
    public function index() {
        $products = Product::orderBy('created_at','DESC')->get();

        return view('products.list',[
            'products' => $products
        ]);
    }

    // This method will show create product page
    public function create() {
        return view('products.create');
    }

    // This method will store a product in db
    public function store(Request $request) {
        $rules = [
            'title' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'due_date' => 'nullable|date',           
        ];

        if ($request->image != "") {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            return redirect()->route('products.create')->withInput()->withErrors($validator);
        }

        // here we will insert product in db
        $product = new Product();
        $product->title = $request->title;
        $product->description = $request->description;
        $product->image = $request->image;
        $product->due_date = $request->due_date;
        $product->save();

        if ($request->image != "") {
            // here we will store image
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext; // Unique image name

            // Save image to products directory
            $image->move(public_path('uploads/products'),$imageName);

            // Save image name in database
            $product->image = $imageName;
            $product->save();
        }        

        return redirect()->route('products.index')->with('success','Product added successfully.');
    }

    // This method will show edit product page
    // public function edit($id) {
    //     $product = Product::findOrFail($id);
    //     return view('products.edit',[
    //         'product' => $product
    //     ]);
    // }

    // // This method will update a product
    // public function update($id, Request $request) {

    //     $product = Product::findOrFail($id);

    //     $rules = [
    //         'title' => 'required',
    //         'description' => 'nullable',
    //         'due_date' => 'nullable|date',
    //         'is_completed' => 'boolean',           
    //     ];

    //     if ($request->image != "") {
    //         $rules['image'] = 'image';
    //     }

    //     $validator = Validator::make($request->all(),$rules);

    //     if ($validator->fails()) {
    //         return redirect()->route('products.edit',$product->id)->withInput()->withErrors($validator);
    //     }

    //     // here we will update product
    //     $product->name = $request->name;
    //     $product->description = $request->description;
    //     $product->due_date = $request->due_date;
    //     $product->is_completed = $request->is_completed;
    //     $product->save();

    //     if ($request->image != "") {

    //         // delete old image
    //         File::delete(public_path('uploads/products/'.$product->image));

    //         // here we will store image
    //         $image = $request->image;
    //         $ext = $image->getClientOriginalExtension();
    //         $imageName = time().'.'.$ext; // Unique image name

    //         // Save image to products directory
    //         $image->move(public_path('uploads/products'),$imageName);

    //         // Save image name in database
    //         $product->image = $imageName;
    //         $product->save();
    //     }        

    //     return redirect()->route('products.index')->with('success','Product updated successfully.');
    // }

    public function edit($id)
{
    $product = Product::findOrFail($id);
    return view('products.edit', compact('product'));
}

public function update($id, Request $request)
{
    $product = Product::findOrFail($id);

    $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'due_date' => 'nullable|date',
        'is_completed' => 'nullable|boolean',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg,webp|max:2048'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return redirect()->route('products.edit', $product->id)
                         ->withInput()
                         ->withErrors($validator);
    }

    $product->title = $request->title;
    $product->description = $request->description;
    $product->due_date = $request->due_date;
    $product->is_completed = $request->has('is_completed') ? 1 : 0;

    // Handle image upload
    if ($request->hasFile('image')) {
        // Delete old image if it exists
        if ($product->image && File::exists(public_path('uploads/products/' . $product->image))) {
            File::delete(public_path('uploads/products/' . $product->image));
        }

        // Save new image
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/products'), $imageName);

        $product->image = $imageName;
    }

    $product->save();

    return redirect()->route('products.index')->with('success', 'Updated successfully.');
}

    // This method will delete a product
    public function destroy($id) {
        $product = Product::findOrFail($id);

       // delete image
       File::delete(public_path('uploads/products/'.$product->image));

       // delete product from database
       $product->delete();

       return redirect()->route('products.index')->with('success','Deleted successfully.');
    }
}