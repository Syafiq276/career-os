# GitHub Integration Setup Guide

## Overview
CareerOS can automatically sync your GitHub repositories as "completed quests" on your portfolio, calculating XP based on stars, forks, and activity.

---

## Setup Steps

### 1. Create GitHub OAuth App

1. Go to [GitHub Developer Settings](https://github.com/settings/developers)
2. Click **"New OAuth App"**
3. Fill in the details:
   - **Application name**: `CareerOS` (or your preferred name)
   - **Homepage URL**: `http://localhost:8000` (or your domain)
   - **Authorization callback URL**: `http://localhost:8000/github/callback`
4. Click **"Register application"**
5. Copy your **Client ID** and **Client Secret**

### 2. Configure Environment Variables

Add to your `.env` file:

```env
GITHUB_CLIENT_ID=your_client_id_here
GITHUB_CLIENT_SECRET=your_client_secret_here
GITHUB_REDIRECT_URI=http://localhost:8000/github/callback
```

### 3. Install Dependencies

```bash
composer require laravel/socialite
```

### 4. Test the Integration

1. **Start your server**:
   ```bash
   php artisan serve
   ```

2. **Login to CareerOS** with your account

3. **Navigate to Profile Settings**:
   - Go to `/profile`
   - Find the "GitHub Integration" section (top card with neon border)

4. **Connect GitHub**:
   - Click **"CONNECT_GITHUB()"** button
   - Authorize the app on GitHub
   - You'll be redirected back to CareerOS

5. **Sync Repositories**:
   - Click **"SYNC_REPOSITORIES()"** button
   - Your public repos will be imported as projects/quests
   - Check your portfolio to see them displayed!

---

## How It Works

### XP Calculation Formula

Each repository earns XP based on:

```php
Base XP: 100
+ Stars × 10 (max 500 XP)
+ Forks × 5 (max 300 XP)
+ Has Description: +50 XP
+ Topics × 10 XP each
+ Updated in last 30 days: +100 XP
```

**Examples:**
- Small personal project (0 stars, no description): **100 XP**
- Active project (5 stars, 2 forks, description): **210 XP**
- Popular repo (50 stars, 20 forks, topics, active): **850+ XP**

### Difficulty Levels

Automatically determined by score = (stars × 2) + forks + (size_kb / 1000):

| Score | Difficulty | Border Color |
|-------|-----------|--------------|
| 0-10 | Tutorial | Green |
| 11-50 | Normal | Blue |
| 51-100 | Hardcore | Purple |
| 100+ | Legendary | Gold |

### Tech Stack Detection

Automatically fetches repository languages from GitHub API:
- Primary language + all detected languages
- Displayed as "Loot" badges on quest cards
- Examples: `['Laravel', 'PHP', 'JavaScript', 'CSS']`

### Featured Projects

A repository becomes featured if:
- It has at least 1 star, OR
- It has a description

Non-featured projects are still imported but not shown on public portfolio.

---

## Features

### ✅ What Gets Synced
- ✅ Repository name → Quest title
- ✅ Description → Quest description
- ✅ Languages → Tech stack (loot)
- ✅ Stars/Forks → XP calculation
- ✅ Last updated → Activity bonus
- ✅ Topics → Extra XP

### ❌ What Doesn't Get Synced
- ❌ Forked repos (unless they have 5+ stars)
- ❌ Private repositories
- ❌ Archived repositories
- ❌ Repository commits/contributors

---

## Sync Behavior

### First Sync
- Fetches all public repositories
- Creates new project entries
- Calculates XP and difficulty
- Marks featured projects

### Subsequent Syncs
- **Updates existing projects** (matched by `repo_link`)
- **Adds new repositories** created since last sync
- **Doesn't delete** manually created projects
- **Preserves manual edits** to non-GitHub fields

### Manual vs Auto-Created Projects
You can still manually create projects that don't come from GitHub:
- GitHub-synced projects have a `repo_link`
- Manual projects don't have `repo_link`
- Both types coexist and contribute to XP/level

---

## Troubleshooting

### "No GitHub token found"
- Make sure you clicked "CONNECT_GITHUB()" first
- Check if authorization succeeded on GitHub
- Verify OAuth credentials in `.env`

### "No repositories found"
- Ensure you have public repositories on GitHub
- Check if the GitHub token has `repo` scope
- Try disconnecting and reconnecting

### Sync Fails Silently
- Check Laravel logs: `storage/logs/laravel.log`
- Verify GitHub API rate limits (60 req/hour unauthenticated, 5000 with token)
- Test GitHub token manually: `curl -H "Authorization: token YOUR_TOKEN" https://api.github.com/user/repos`

### Repos Not Showing on Portfolio
- Only **featured** projects appear on public portfolio
- Check `is_featured` flag in database
- Requirements: 1+ stars OR has description

---

## API Endpoints

### Routes Added
```php
GET  /github/redirect    → Redirect to GitHub OAuth
GET  /github/callback    → Handle OAuth callback
POST /github/sync        → Sync repositories
POST /github/disconnect  → Disconnect GitHub account
```

### GitHub API Calls
```php
// Get user repositories
GET https://api.github.com/user/repos

// Get repository details
GET https://api.github.com/repos/{owner}/{repo}

// Get languages
GET https://api.github.com/repos/{owner}/{repo}/languages
```

---

## Security

### OAuth Scopes Requested
- `read:user` - Read basic user info
- `repo` - Access public repositories

### Token Storage
- GitHub tokens stored encrypted in `users.github_token`
- Never exposed in views or frontend
- Used only for server-side API calls

### Best Practices
- ✅ Use environment variables for secrets
- ✅ Validate all GitHub API responses
- ✅ Log errors without exposing tokens
- ✅ Handle rate limiting gracefully

---

## Advanced: Custom XP Formula

Want to adjust XP calculation? Edit `App\Services\GitHubService::calculateXpFromRepo()`:

```php
public function calculateXpFromRepo(array $repo): int
{
    $baseXp = 200; // Increase base XP
    $starXp = min($repo['stargazers_count'] * 20, 1000); // Double star value
    // ... customize your formula
    return $baseXp + $starXp + /* ... */;
}
```

---

## Production Deployment

### Environment Variables
```env
# Production settings
GITHUB_CLIENT_ID=prod_client_id
GITHUB_CLIENT_SECRET=prod_secret
GITHUB_REDIRECT_URI=https://yourdomain.com/github/callback
```

### OAuth App Updates
1. Create a **new GitHub OAuth App** for production
2. Set production callback URL
3. Update `.env` with production credentials

### Rate Limits
- Authenticated: 5,000 requests/hour
- Monitor usage if you have many users
- Consider caching GitHub data

---

## Future Enhancements

- [ ] Scheduled auto-sync (daily cron job)
- [ ] Webhook support for real-time updates
- [ ] Import specific repos (not all)
- [ ] Display repo statistics (commits, contributors)
- [ ] GitHub Activity timeline
- [ ] Support for GitLab/Bitbucket

---

## Resources

- [GitHub OAuth Documentation](https://docs.github.com/en/developers/apps/building-oauth-apps)
- [GitHub REST API](https://docs.github.com/en/rest)
- [Laravel Socialite](https://laravel.com/docs/socialite)
- [CareerOS Scalability Guide](./SCALABILITY.md)
