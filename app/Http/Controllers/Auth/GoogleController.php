<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = $this->syncUser($googleUser);
        } catch (Throwable $exception) {
            Log::error('Google authentication failed.', [
                'message' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('welcome')
                ->with('status', 'Google sign-in could not be completed. Please try again.');
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('welcome');
    }

    private function syncUser(SocialiteUser $googleUser): User
    {
        if (! $googleUser->getEmail()) {
            throw new \RuntimeException('Google account email is required.');
        }

        return User::query()->updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Worship Leader',
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Str::password(32),
                'email_verified_at' => now(),
            ]
        );
    }
}
