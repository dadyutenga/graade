<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // If user is logged in, redirect to dashboard
        if (auth()->loggedIn()) {
            return redirect()->to('/dashboard');
        }
        
        return view('welcome_message', [
            'title' => 'Welcome to My Application'
        ]);
    }
}
