<?php

namespace App\Http\Controllers;

class MarketingPageController extends Controller
{
    public function about()
    {
        return view('marketing.about');
    }

    public function contact()
    {
        return view('marketing.contact');
    }

    public function privacy()
    {
        return view('marketing.privacy');
    }

    public function terms()
    {
        return view('marketing.terms');
    }

    public function plans()
    {
        return view('marketing.plans');
    }

    public function help()
    {
        return view('marketing.help');
    }

    public function faq()
    {
        return view('marketing.faq');
    }

    public function products()
    {
        return view('marketing.products');
    }

    public function featureRequests()
    {
        return view('marketing.feature-requests');
    }
}
