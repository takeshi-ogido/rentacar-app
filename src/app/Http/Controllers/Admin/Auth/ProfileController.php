<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;


class ProfileController extends Controller
{
    /**
     * Show the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'admin' => $request->user('admin'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse    {
        $admin = $request->user('admin');
        $admin->fill($request->validated());

        if ($admin->isDirty('email')) {
            $admin->email_verified_at = null;
        }

        $admin->save();

        return redirect()->route('admin.profile.edit')->with('status', 'プロフィールを更新しました。');
    }

    /*
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validate the current password for account deletion
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password:admin'], 
        ]);
    
        $admin = $request->user('admin');

        Auth::guard('admin')->logout();

        $admin->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');    
    }
};