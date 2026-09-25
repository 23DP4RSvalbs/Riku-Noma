<?php

namespace App\Http\Controllers;

use App\Models\Kategorija;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Kategorija::orderBy('kategorijaID')->get());
    }
}
