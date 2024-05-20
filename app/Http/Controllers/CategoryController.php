<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\UploadBannerRequest;
use App\Http\Requests\CategoryStoreRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CategoryService::getAll();
    }

    public function getPersonals()
    {
        return CategoryService::getPersonals();
    }

    public function uploadBanner(UploadBannerRequest $request)
    {
        return CategoryService::uploadBanner($request);
    }

    public function Store(CategoryStoreRequest $request)
    {
        return CategoryService::store($request);
    }
}
