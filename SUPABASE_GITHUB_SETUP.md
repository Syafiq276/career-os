# 🔧 Supabase Integration Guide

## Setting Up Supabase with CareerOS

### 1. Create Supabase Project

1. Go to https://supabase.com and sign up/login
2. Click "New Project"
3. Fill in:
   - **Name**: `careeros`
   - **Database Password**: Generate a strong password (save it!)
   - **Region**: Choose closest to your users
   - **Pricing Plan**: Free (perfect for development)
4. Wait ~2 minutes for project to initialize

### 2. Get Database Credentials

1. In Supabase Dashboard → **Settings** → **Database**
2. Under "Connection string", select **URI**
3. Copy the connection string (looks like):
   ```
   postgresql://postgres:[YOUR-PASSWORD]@db.xxxxx.supabase.co:5432/postgres
   ```

### 3. Configure Laravel .env

Update your `.env` file with Supabase credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=db.xxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-database-password
```

### 4. Run Migrations

```bash
php artisan migrate
php artisan db:seed
```

### 5. Supabase Real-time Features (Optional)

Install Supabase PHP client for real-time features:

```bash
composer require supabase/supabase-php
```

Configure in `config/services.php`:

```php
'supabase' => [
    'url' => env('SUPABASE_URL'),
    'key' => env('SUPABASE_ANON_KEY'),
],
```

Add to `.env`:

```env
SUPABASE_URL=https://xxxxx.supabase.co
SUPABASE_ANON_KEY=your-anon-key
```

---

# 🔗 GitHub Integration Guide

## Setting Up Dynamic GitHub Projects

### 1. Create GitHub Personal Access Token

1. Go to GitHub → **Settings** → **Developer settings** → **Personal access tokens** → **Tokens (classic)**
2. Click "Generate new token (classic)"
3. Give it a name: `CareerOS API`
4. Select scopes:
   - ✅ `public_repo` (access public repositories)
   - ✅ `read:user` (read user profile)
5. Click "Generate token"
6. **Copy the token immediately** (you won't see it again!)

### 2. Add Token to .env

```env
GITHUB_TOKEN=ghp_xxxxxxxxxxxxxxxxxxxx
```

### 3. Usage

#### Sync GitHub Repos to Projects

```bash
# Visit in browser (must be logged in)
http://localhost:8888/github/sync-repos
```

This page will:
- ✅ Show all your public repositories
- ✅ Display pinned repos at the top
- ✅ Let you select which repos to sync
- ✅ Auto-calculate difficulty and XP
- ✅ Extract tech stack from repo languages
- ✅ Prevent duplicate syncing

#### Programmatic Usage

```php
use App\Services\GitHubService;

$githubService = app(GitHubService::class);

// Get user's repos
$repos = $githubService->getUserRepositories('your-username');

// Get pinned repos
$pinned = $githubService->getPinnedRepositories('your-username');

// Sync all repos to projects
$result = $githubService->syncRepositoriesToProjects($user);

// Get repo details
$repo = $githubService->getRepositoryDetails('your-username', 'repo-name');
```

### 4. How XP & Difficulty Are Calculated

**Difficulty Levels:**
- 🟡 **Legendary**: 50+ stars OR 5+ languages
- 🟣 **Hardcore**: 10+ stars OR 3+ languages  
- 🔵 **Normal**: 5+ stars OR 2+ languages
- 🟢 **Easy**: Everything else

**XP Formula:**
```
Base XP: 50
+ (Stars × 10)
+ (Forks × 5)
+ (Languages × 20)
= Total XP (capped at 1000)
```

### 5. Add Navigation Link

Update `resources/views/layouts/navigation.blade.php`:

```php
<x-nav-link :href="route('github.sync-repos')" :active="request()->routeIs('github.sync-repos')">
    {{ __('Sync GitHub Projects') }}
</x-nav-link>
```

---

## 🎯 Benefits of GitHub Integration

### Dynamic Portfolio
- ✅ Automatically showcase your latest projects
- ✅ No manual data entry for tech stacks
- ✅ Real GitHub metrics (stars, forks)
- ✅ Always up-to-date project descriptions

### Time Saver
- ✅ Bulk import multiple repos at once
- ✅ Smart filtering (pinned repos highlighted)
- ✅ Duplicate detection prevents re-syncing
- ✅ One-click sync for all repos

### Gamification
- ✅ Auto-calculated XP based on repo popularity
- ✅ Difficulty levels from repo complexity
- ✅ Level up as you create more projects
- ✅ RPG-style quest board powered by real GitHub data

---

## 🚀 Production Deployment

### Render with Supabase

1. Use Supabase database instead of Render PostgreSQL (more features!)
2. Update `render.yaml` database section to use external database:

```yaml
services:
  - type: web
    name: careeros
    runtime: docker
    plan: free
    dockerfilePath: ./Dockerfile.render
    dockerContext: .
    envVars:
      - key: DB_CONNECTION
        value: pgsql
      - key: DB_HOST
        value: db.xxxxx.supabase.co
      - key: DB_PORT
        value: 5432
      - key: DB_DATABASE
        value: postgres
      - key: DB_USERNAME
        value: postgres
      - key: DB_PASSWORD
        sync: false # Set manually in Render dashboard
      - key: GITHUB_TOKEN
        sync: false # Set manually
```

3. In Render Dashboard, add environment variables manually:
   - `DB_PASSWORD` → Your Supabase password
   - `GITHUB_TOKEN` → Your Personal Access Token

### Security Notes

- ⚠️ **Never commit `.env` to GitHub**
- ⚠️ **Keep tokens secret** - use environment variables
- ⚠️ **Rate Limits**: GitHub API has 60 requests/hour (unauthenticated) or 5000/hour (authenticated with token)
- ✅ **Caching**: Service caches API responses for 1 hour to avoid rate limits

---

## 📊 Example Workflow

1. **User registers** on CareerOS
2. **Sets GitHub username** in profile
3. **Visits GitHub Sync page** (`/github/sync-repos`)
4. **Selects favorite repos** to showcase
5. **Clicks "Sync Selected"**
6. **Projects appear** in dashboard with XP/difficulty
7. **Public portfolio** (`/`) automatically shows GitHub projects
8. **Visitors see** dynamic, real-time project showcase

---

Built with ❤️ and { code }
