<?php

namespace App\Http\Controllers;

use App\Templates\Template;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(Template $template): View
    {
        return view('template::home', $template->homeData());
    }
}
