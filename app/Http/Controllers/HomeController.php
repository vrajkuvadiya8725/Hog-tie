<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ClientQuote;
use App\Models\Faq;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $categories = Category::with(['products' => function ($query) use ($search) {
            if ($search !== '') {
                $query->where('name', 'like', '%'.$search.'%');
            }
            $query->orderBy('name');
        }])->orderBy('name')->get();

        return view('home', [
            'categories' => $categories,
            'faqs' => Faq::latest()->get(),
            'testimonials' => Testimonial::latest()->get(),
            'clientQuotes' => ClientQuote::latest()->get(),
        ]);
    }
}
