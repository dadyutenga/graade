<?php

namespace App\Controllers;

use CodeIgniter\Shield\Controllers\LoginController;
use CodeIgniter\Shield\Controllers\RegisterController;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\HTTP\RedirectResponse;

class AuthController extends BaseController
{
    /**
     * Display the login view
     */
    public function login()
    {
        // Check if already logged in
        if (auth()->loggedIn()) {
            return redirect()->to('/dashboard');
        }
        
        return view('auth/login');
    }

    /**
     * Attempt to log the user in
     */
    public function attemptLogin()
    {
        // Validate the form data
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Get the credentials from the form
        $credentials = [
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];

        // Attempt to login
        $result = auth()->attempt($credentials);
        
        if (! $result->isOK()) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result->reason());
        }

        // If remember me was checked
        if ($this->request->getPost('remember') === 'on') {
            auth()->remember();
        }

        // Redirect to the intended page
        return redirect()->to(config('Auth')->loginRedirect());
    }

    /**
     * Display the registration view
     */
    public function register()
    {
        // Check if already logged in
        if (auth()->loggedIn()) {
            return redirect()->to('/dashboard');
        }
        
        // Check if registration is allowed
        if (! config('Auth')->allowRegistration) {
            return redirect()->to('/login')
                ->with('error', 'Registration is currently disabled.');
        }
        
        return view('auth/register');
    }

    /**
     * Attempt to register a new user
     */
    public function attemptRegister()
    {
        // Check if registration is allowed
        if (! config('Auth')->allowRegistration) {
            return redirect()->to('/login')
                ->with('error', 'Registration is currently disabled.');
        }

        // Validate the form data
        $rules = [
            'email' => 'required|valid_email|is_unique[users.email]',
            'username' => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Get the data from the form
        $users = model('UserModel');
        
        $user = new \CodeIgniter\Shield\Entities\User([
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ]);

        // Save the user
        $users->save($user);

        // Add to default group
        $users->addToDefaultGroup($user);

        // Success!
        return redirect()->to('/login')
            ->with('message', 'Registration successful. Please login.');
    }

    /**
     * Log the user out
     */
    public function logout()
    {
        auth()->logout();
        
        return redirect()->to('/login')
            ->with('message', 'You have been logged out.');
    }
} 