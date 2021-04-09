<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'star' => 'required|array|min:1',
            'star.*' => 'max:5',
            'text' => 'max:500',
        ]);

        $review = new Review();
        $review->stars = sizeof($validated["star"]);
        $review->text = $validated["text"] === NULL ? "" : $validated["text"];
        $review->user_id = auth()->user()->id;
        $review->product_id = $request->input('productid');

        $review->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $r = Review::where('id', $id)->firstOrFail();
        if (auth()->check() && (auth()->user()->id == $r->user_id || auth()->user()->is_admin)) {
            $pid = $r->product_id;
            $r->delete();
            return redirect('/product/' . $pid);
        }

        return redirect()->withErrors(["You don't have enough permissions to delete this!"]);
    }
}
