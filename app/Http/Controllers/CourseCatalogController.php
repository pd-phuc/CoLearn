<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::with(['teacher', 'category', 'sections.lessons'])
            ->published();

        // Search query
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->input('category'));
            });
        }

        // Level filter
        if ($request->filled('level') && in_array($request->input('level'), ['beginner', 'intermediate', 'advanced'])) {
            $query->where('level', $request->input('level'));
        }

        // Price filter
        if ($request->filled('price')) {
            if ($request->input('price') === 'free') {
                $query->where('price', 0);
            } elseif ($request->input('price') === 'paid') {
                $query->where('price', '>', 0);
            }
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $courses = $query->paginate(9)->withQueryString();
        $categories = Category::where('is_active', true)->withCount('courses')->get();

        return view('courses.index', compact('courses', 'categories'));
    }
}
