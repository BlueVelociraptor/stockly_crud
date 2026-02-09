<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post("/product/save", [ProductController::class, "saveProductEndpoint"])->name("product.save");
