<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

use GuzzleHttp\Handler\Proxy;

class AdminController extends Controller
{
    // --------Category --------------
    public function view_category()
    {
        $data = Category::all();
        return view('admin.category', compact('data'));
    }
    public function add_category(Request $request)
    {
        $category = new Category();
        $category->category_name = $request->category_name;
        $category->save();
        return redirect()->back()->with('message', 'Category added successfully.');
    }

    public function delete_category($id)
    {
        $category = Category::find($id);
        $category->delete();
        return redirect()->back()->with('message', 'Category deleted successfully.');
    }
    public function update_category($id)
    {
        $category = Category::find($id);
        return view('admin.update_category', compact('category'));
    }
    public function edit_category(Request $request, $id)
    {
        $category = Category::find($id);
        $category->category_name = $request->category_name;
        $category->save();
        return redirect('view_category')->with('message', 'Category updated successfully.');
    }

    // --------Product --------------
    public function view_product()
    {
        $category = Category::all();
        return view('admin.add_product', compact('category'));
    }

    public function add_product(Request $request)
    {
        $product = new Product;
        $product->title = $request->title;
        $product->description = $request->description;
        $product->category = $request->product_category;
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->dicount_price = $request->discount_price;

        $image = $request->product_image;
        $imagename = time() . '.' . $image->getClientOriginalExtension();
        $request->product_image->move('product', $imagename);
        $product->image = $imagename;
        $product->save();
        return redirect()->back()->with('message', 'Product Addedd Sucessfully');
    }

    public function show_product()
    {
        $product = Product::all();
        return view('admin.show_product', compact('product'));
    }

    public function delete_product($id)
    {
        $product = Product::find($id);
        $product->delete();
        return redirect()->back()->with('message', 'Product deleted successfully.');
    }

    public function update_product($id)
    {
        $product = Product::find($id);
        $category = Category::all();
        return view('admin.update_product', compact('product', 'category'));
    }

    public function edit_product(Request $request, $id)
    {
        $product = Product::find($id);
        $product->title = $request->title;
        $product->description = $request->description;
        $product->category = $request->product_category;
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->dicount_price = $request->discount_price;

        $image = $request->product_image;
        if ($image) {
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $request->product_image->move('product', $imagename);
            $product->image = $imagename;
        }
        $product->save();
        return redirect()->back()->with('message', 'Product updated successfully.');
    }
}
