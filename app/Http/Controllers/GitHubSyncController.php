<?php

namespace App\Http\Controllers;

use App\Services\GitHubService;
use Illuminate\Http\Request;

class GitHubSyncController extends Controller
{
    protected $githubService;

    public function __construct(GitHubService $githubService)
    {
        $this->githubService = $githubService;
    }

    /**
     * Show GitHub sync page
     */
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->github_username) {
            return redirect()->route('profile.edit')
                ->with('error', 'Please set your GitHub username first.');
        }

        // Get user's repositories
        $repos = $this->githubService->getUserRepositories($user->github_username);
        
        // Get pinned repos
        $pinnedRepos = $this->githubService->getPinnedRepositories($user->github_username);
        
        // Get already synced repos
        $syncedRepos = $user->projects()
            ->whereNotNull('repo_link')
            ->pluck('repo_link')
            ->map(function($url) {
                // Extract repo name from URL
                return basename($url);
            })
            ->all();

        return view('github.sync', compact('repos', 'pinnedRepos', 'syncedRepos'));
    }

    /**
     * Sync selected repositories
     */
    public function sync(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'repos' => 'required|array',
            'repos.*' => 'string'
        ]);

        $result = $this->githubService->syncRepositoriesToProjects(
            $user, 
            $request->repos
        );

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        return redirect()->route('applications.index')
            ->with('success', "Successfully synced {$result['synced']} repositories!");
    }

    /**
     * Sync all repositories
     */
    public function syncAll()
    {
        $user = auth()->user();
        
        $result = $this->githubService->syncRepositoriesToProjects($user);

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        return redirect()->route('applications.index')
            ->with('success', "Successfully synced {$result['synced']} out of {$result['total_repos']} repositories!");
    }

    /**
     * Preview repository as project
     */
    public function preview(Request $request)
    {
        $user = auth()->user();
        $repoName = $request->query('repo');

        if (!$user->github_username || !$repoName) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $repoDetails = $this->githubService->getRepositoryDetails(
            $user->github_username, 
            $repoName
        );

        if (!$repoDetails) {
            return response()->json(['error' => 'Repository not found'], 404);
        }

        $projectData = $this->githubService->repoToProject($repoDetails);

        return response()->json($projectData);
    }
}
