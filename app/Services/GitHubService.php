<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GitHubService
{
    protected $baseUrl = 'https://api.github.com';
    
    /**
     * Fetch user's repositories from GitHub.
     */
    public function getUserRepositories(string $accessToken, int $perPage = 100): array
    {
        try {
            $response = Http::withToken($accessToken)
                ->accept('application/vnd.github.v3+json')
                ->get("{$this->baseUrl}/user/repos", [
                    'per_page' => $perPage,
                    'sort' => 'updated',
                    'affiliation' => 'owner',
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('GitHub API error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('GitHub API exception', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get detailed information about a specific repository.
     */
    public function getRepository(string $accessToken, string $owner, string $repo): ?array
    {
        try {
            $response = Http::withToken($accessToken)
                ->accept('application/vnd.github.v3+json')
                ->get("{$this->baseUrl}/repos/{$owner}/{$repo}");

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('GitHub repo fetch error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get languages used in a repository.
     */
    public function getRepositoryLanguages(string $accessToken, string $owner, string $repo): array
    {
        try {
            $response = Http::withToken($accessToken)
                ->accept('application/vnd.github.v3+json')
                ->get("{$this->baseUrl}/repos/{$owner}/{$repo}/languages");

            return $response->successful() ? array_keys($response->json()) : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Calculate XP based on repository metrics.
     */
    public function calculateXpFromRepo(array $repo): int
    {
        $baseXp = 100;
        
        // Add XP based on stars
        $starXp = min($repo['stargazers_count'] * 10, 500);
        
        // Add XP based on forks
        $forkXp = min($repo['forks_count'] * 5, 300);
        
        // Bonus for repos with topics/description
        $descriptionBonus = !empty($repo['description']) ? 50 : 0;
        $topicsBonus = count($repo['topics'] ?? []) * 10;
        
        // Bonus for active repos (updated recently)
        $updatedAt = \Carbon\Carbon::parse($repo['updated_at']);
        $activeBonus = $updatedAt->diffInDays(now()) < 30 ? 100 : 0;
        
        return $baseXp + $starXp + $forkXp + $descriptionBonus + $topicsBonus + $activeBonus;
    }

    /**
     * Determine project difficulty based on repository metrics.
     */
    public function determineDifficulty(array $repo): string
    {
        $stars = $repo['stargazers_count'];
        $forks = $repo['forks_count'];
        $size = $repo['size']; // KB
        
        $score = ($stars * 2) + $forks + ($size / 1000);
        
        if ($score > 100) {
            return 'Legendary';
        } elseif ($score > 50) {
            return 'Hardcore';
        } elseif ($score > 10) {
            return 'Normal';
        }
        
        return 'Tutorial';
    }

    /**
     * Sync user's GitHub repositories to projects.
     */
    public function syncRepositories(\App\Models\User $user): array
    {
        if (!$user->github_token) {
            return [
                'success' => false,
                'message' => 'No GitHub token found. Please connect your GitHub account.',
            ];
        }

        $repos = $this->getUserRepositories($user->github_token);
        
        if (empty($repos)) {
            return [
                'success' => false,
                'message' => 'No repositories found or unable to fetch from GitHub.',
            ];
        }

        $synced = 0;
        $errors = [];

        foreach ($repos as $repo) {
            try {
                // Skip forks unless they have significant changes
                if ($repo['fork'] && $repo['stargazers_count'] < 5) {
                    continue;
                }

                // Get languages for tech stack
                $languages = $this->getRepositoryLanguages(
                    $user->github_token,
                    $repo['owner']['login'],
                    $repo['name']
                );

                // Create or update project
                $user->projects()->updateOrCreate(
                    ['repo_link' => $repo['html_url']],
                    [
                        'title' => $repo['name'],
                        'description' => $repo['description'] ?? 'No description provided.',
                        'difficulty' => $this->determineDifficulty($repo),
                        'tech_stack' => $languages,
                        'xp_gained' => $this->calculateXpFromRepo($repo),
                        'is_featured' => $repo['stargazers_count'] > 0 || !empty($repo['description']),
                    ]
                );

                $synced++;
            } catch (\Exception $e) {
                $errors[] = "Failed to sync {$repo['name']}: {$e->getMessage()}";
            }
        }

        return [
            'success' => true,
            'synced' => $synced,
            'total' => count($repos),
            'errors' => $errors,
        ];
    }
}
