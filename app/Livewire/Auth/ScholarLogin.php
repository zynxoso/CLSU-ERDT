<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Announcement;

class ScholarLogin extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => ['required', 'email'],
        'password' => ['required'],
    ];

    public function login()
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return redirect()->intended(route('scholar.dashboard'));
    }

    public function render()
    {
        // Get active announcements for display
        $announcements = Announcement::active()
            ->published()
            ->orderByPriority()
            ->limit(4)
            ->get();

        return view('livewire.auth.scholar-login', [
            'announcements' => $announcements
        ])->layout('layouts.guest');
    }
}
