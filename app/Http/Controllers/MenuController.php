<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->whereHas('menuItems', fn ($query) => $query->where('is_available', true))
            ->with(['menuItems' => fn ($query) => $query->where('is_available', true)->orderBy('name')])
            ->orderBy('name')
            ->get();

        return view('menu.index', compact('categories'));
    }
}
