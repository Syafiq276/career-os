<?php

namespace App\Http\Controllers;

use App\Services\GitHubService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GitHubController extends Controller
{
    protected $githubService;

    public function __construct(GitHubService $githubService)
    {
        $this->middleware('auth');
        $this->githubService = $githubService;
    }

    /**
     * Redirect to GitHub OAuth page.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('github')
            ->scopes(['read:user', 'repo'])
            ->redirect();
    }

    /**
     * Handle GitHub OAuth callback.
     */
    public function callback(): RedirectResponse
    {
        try {
            $githubUser = Socialite::driver('github')->user();
            
            // Update user with GitHub token and username
            auth()->user()->update([
                'github_username' => $githubUser->getNickname(),
                'github_token' => $githubUser->token,
            ]);

            return redirect()->route('profile.edit')
                ->with('status', 'github-connected')
                ->with('message', 'GitHub account connected successfully! You can now sync your repositories.');
                
        } catch (\Exception $e) {
            return redirect()->route('profile.edit')
                ->with('error', 'Failed to connect GitHub account: ' . $e->getMessage());
        }
    }

    /**
     * Disconnect GitHub account.
     */
    public function disconnect(): RedirectResponse
    {
        auth()->user()->update([
            'github_token' => null,
        ]);

        return redirect()->route('profile.edit')
            ->with('status', 'github-disconnected')
            ->with('message', 'GitHub account disconnected.');
    }

    /**
     * Sync GitHub repositories to projects.
     */
    public function sync(): RedirectResponse
    {
        $result = $this->githubService->syncRepositories(auth()->user());

        if ($result['success']) {
            $message = "Successfully synced {$result['synced']} out of {$result['total']} repositories!";
            
            if (!empty($result['errors'])) {
                $message .= ' Some repositories failed to sync.';
            }

            return redirect()->route('profile.edit')
                ->with('status', 'repos-synced')
                ->with('message', $message);
        }

        return redirect()->route('profile.edit')
            ->with('error', $result['message']);
    }
}
