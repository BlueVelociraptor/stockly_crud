<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post("/product/save", [ProductController::class, "saveProductEndpoint"])->name("product.save");
Route::get("/product/all", [ProductController::class, "getAllProductEndpoint"])->name("product.all");
Route::get("/product/{id}", [ProductController::class, "getProductByIdEndpoint"])->name("product.oneById");
Route::patch("/product/update-status/{id}", [ProductController::class, "updateProductStatusEndpoint"])->name("product.updateStatus");
