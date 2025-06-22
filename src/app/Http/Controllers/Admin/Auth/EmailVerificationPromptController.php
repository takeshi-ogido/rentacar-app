<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user('admin')->hasVerifiedEmail() // Explicitly use 'admin' guard if necessary
        ? redirect()->intended(route('admin.dashboard', absolute: false)) // Redirect to admin dashboard
        : view('admin.auth.verify-email'); // Use an admin-specific view    }
    }
}
