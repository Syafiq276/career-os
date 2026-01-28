<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to OAuth provider.
     */
    public function redirect(string $provider)
    {
        $this->validateProvider($provider);
        
        return Socialite::driver($provider)
            ->scopes($this->getScopes($provider))
            ->redirect();
    }

    /**
     * Handle OAuth callback.
     */
    public function callback(string $provider)
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Find or create user
            $user = $this->findOrCreateUser($socialUser, $provider);
            
            // Log the user in
            Auth::login($user, true);
            
            return redirect()->intended(route('applications.index'))
                ->with('status', 'Welcome back! You\'re now logged in.');
                
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['oauth' => 'Failed to authenticate with ' . ucfirst($provider) . '. Please try again.']);
        }
    }

    /**
     * Find or create user from OAuth provider.
     */
    protected function findOrCreateUser($socialUser, string $provider): User
    {
        // Try to find user by email first
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Update OAuth token if user exists
            $this->updateUserOAuthData($user, $socialUser, $provider);
            return $user;
        }

        // Create new user
        return User::create([
            'name' => $this->extractUsername($socialUser),
            'email' => $socialUser->getEmail(),
            'password' => Hash::make(Str::random(24)), // Random password (not used for OAuth)
            'email_verified_at' => now(),
            'github_username' => $provider === 'github' ? $socialUser->getNickname() : null,
            'github_token' => $provider === 'github' ? $socialUser->token : null,
            'is_profile_public' => false,
        ]);
    }

    /**
     * Update user's OAuth data.
     */
    protected function updateUserOAuthData(User $user, $socialUser, string $provider): void
    {
        $updates = [];

        if ($provider === 'github') {
            $updates['github_username'] = $socialUser->getNickname();
            $updates['github_token'] = $socialUser->token;
        }

        if (!empty($updates)) {
            $user->update($updates);
        }
    }

    /**
     * Extract username from OAuth provider data.
     */
    protected function extractUsername($socialUser): string
    {
        // Try nickname first (GitHub)
        if ($nickname = $socialUser->getNickname()) {
            return $nickname;
        }

        // Try name
        if ($name = $socialUser->getName()) {
            return $name;
        }

        // Fallback to email username
        return explode('@', $socialUser->getEmail())[0];
    }

    /**
     * Get OAuth scopes for provider.
     */
    protected function getScopes(string $provider): array
    {
        return match($provider) {
            'github' => ['read:user', 'repo'],
            'google' => [],
            default => [],
        };
    }

    /**
     * Validate OAuth provider.
     */
    protected function validateProvider(string $provider): void
    {
        if (!in_array($provider, ['github', 'google'])) {
            abort(404, 'Invalid OAuth provider');
        }
    }
}
