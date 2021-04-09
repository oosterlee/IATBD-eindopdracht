<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\ProductImage;

class ImageController extends Controller {
    public function show($id) {
    	$img = ProductImage::where('id', $id)->firstOrFail()->image;
    	return response($img)->header("Content-Type", 'image/png');
    }
}