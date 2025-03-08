<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Pass authentication status to the view
        $data = [
            'isLoggedIn' => auth()->loggedIn(),
            'user' => auth()->loggedIn() ? auth()->user() : null
        ];
        
        return view('welcome_message', $data);
    }
}
