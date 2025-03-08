<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        // Check if user is logged in
        if (! auth()->loggedIn()) {
            return redirect()->to('/login');
        }
        
        return view('dashboard');
    }
} 