<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductLoan;
use Illuminate\Http\Request;
use Illuminate\Http\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('layouts.product.index', [
            'products' => Product::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('forms.product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:40',
            'description' => 'required|max:500',
            'categories' => 'required|array|max:10',
            'categories.*' => 'required|max:20',
            'image' => 'array|max:20',
            'image.*' => 'required|image|max:5000',
            'listed' => 'required|boolean',
            'available' => 'required|boolean',
            'days_to_loan' => 'required|numeric|min:1|max:365'
        ]);

        $product = new Product();

        $product->name = $validated["name"];
        $product->description = $validated["description"];
        $product->listed = $validated["listed"];
        $product->available = $validated["available"];
        $product->user_id = auth()->user()->id;
        $product->days_to_loan = $validated["days_to_loan"];

        if ($product->save()) {
            foreach($validated["categories"] as $key => $value) {
                $category = new ProductCategory();
                $category->category = $value;
                $category->product_id = $product->id;
                $category->save();
            }
            $images = $request->file("image");
            foreach($images as $key => $value) {
                $image = new ProductImage();
                $image->image = $value->getContent();
                $image->product_id = $product->id;
                $image->save();
            }
            return redirect('/product/' . $product->id);
        }
        return redirect()->back()->withErrors(["Something went wrong, please try again later."]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $p = Product::where('id', $id)->firstOrFail();
        return view('layouts.product.show')->with(
            [
                'product' => $p,
                'user' => $p->user,
                'categories' => $p->categories,
                'reviews' => $p->reviews,
                'images' => $p->images
            ]
        );
        // return Product::where('id', $id)->firstOrFail();
    }

    public function showUserProducts($id) {
        return view('layouts.product.index', [
            'user' => User::where('id', $id)->firstOrFail(),
            'products' => Product::where("user_id", $id)->get(),
            'loaned_products' => auth()->check() ? ProductLoan::where('user_id', auth()->user()->id)->get() : null,
            'products_loaned_from_me' => auth()->check() ? Product::where("user_id", auth()->user()->id)->get() : null,
        ]);
    }

    public function loanProduct(Request $request, $id) {
        $productLoan = new ProductLoan();

        $product = Product::where('id', $id)->firstOrFail();

        $productLoan->product_id = $id;
        $productLoan->loanDate = date('Y-m-d H:i:s');
        $productLoan->deadline = date('Y-m-d H:i:s', strtotime($productLoan->loanDate . '+ ' . $product->days_to_loan . ' Days'));
        $productLoan->returned = false;
        $productLoan->user_id = auth()->user()->id;

        $productLoan->save();

        return redirect('/products/' . auth()->user()->id);
    }

    public function productReturned(Request $request, $id) {
        $loan = ProductLoan::where('id', $id)->firstOrFail();
        if ($loan->product->user->id == auth()->user()->id) {
            $loan->returned = true;
            $loan->save();
            return redirect()->back();
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $product = Product::where('id', $id)->firstOrFail();

        if (auth()->check() && (auth()->user()->id !== $product->user_id && !auth()->user()->is_admin)) {
            return redirect()->back()->withErrors(["You don't have enough permissions to delete this!"]);
        }
        return view('forms.product.edit', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'required|max:40',
            'description' => 'required|max:500',
            'categories' => 'required|array|max:10',
            'categories.*' => 'required|max:20',
            'image' => 'array|max:20',
            'image.*' => 'required|image|max:5000',
            'listed' => 'required|boolean',
            'available' => 'required|boolean',
            'days_to_loan' => 'required|numeric|min:1|max:365'
        ]);

        $product = Product::where('id', $id)->firstOrFail();

        if (auth()->check() && (auth()->user()->id !== $product->user_id && !auth()->user()->is_admin)) {
            return redirect()->withErrors(["You don't have enough permissions to delete this!"]);
        }

        $product->name = $validated["name"];
        $product->description = $validated["description"];
        $product->listed = $validated["listed"];
        $product->available = $validated["available"];
        $product->user_id = auth()->user()->id;
        $product->days_to_loan = $validated["days_to_loan"];

        if ($product->save()) {
            foreach(ProductCategory::where('product_id', $id)->get() as $category) {
                $category->delete();
            }
            foreach(ProductImage::where('product_id', $id)->get() as $image) {
                $image->delete();
            }
            foreach($validated["categories"] as $key => $value) {
                $category = new ProductCategory();
                $category->category = $value;
                $category->product_id = $product->id;
                $category->save();
            }
            $images = $request->file("image");
            foreach($images as $key => $value) {
                $image = new ProductImage();
                $image->image = $value->getContent();
                $image->product_id = $product->id;
                $image->save();
            }
            return redirect('/product/' . $product->id);
        }
        return redirect()->back()->withErrors(["Something went wrong, please try again later."]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $product = Product::where('id', $id)->firstOrFail();
        if (auth()->check() && (auth()->user()->id == $product->user_id || auth()->user()->is_admin)) {
            $product->delete();
            return redirect('/products');
        }
        return redirect()->withErrors(["You don't have enough permissions to delete this!"]);
    }

    public function blockUser(Request $request, $id) {
        if (auth()->check() && auth()->user()->is_admin) {
            $user = User::where('id', $id)->firstOrFail();
            $user->blocked = true;
            $user->save();
            
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function unblockUser(Request $request, $id) {
        if (auth()->check() && auth()->user()->is_admin) {
            $user = User::where('id', $id)->firstOrFail();
            $user->blocked = false;
            $user->save();
            return redirect()->back();
        }

        return redirect()->back();
    }

}
