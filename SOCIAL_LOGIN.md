# Social Login Integration Guide

CareerOS supports social authentication via **GitHub** and **Google OAuth**, allowing users to quickly create accounts and log in without passwords.

---

## Features

✅ **One-Click Registration** - Create account in seconds with GitHub or Google  
✅ **Secure Authentication** - OAuth 2.0 protocol, no password storage needed  
✅ **Auto-Profile Setup** - Username, email automatically imported  
✅ **GitHub Token Sync** - GitHub login also enables repository syncing  
✅ **Cyberpunk UI** - Styled social buttons match CareerOS theme  

---

## Setup Instructions

### 1. GitHub OAuth Setup

#### Create GitHub OAuth App

1. Go to [GitHub Developer Settings](https://github.com/settings/developers)
2. Click **"New OAuth App"**
3. Fill in the details:
   - **Application name**: `CareerOS (Local Development)`
   - **Homepage URL**: `http://localhost:8000`
   - **Authorization callback URL**: `http://localhost:8000/auth/github/callback`
4. Click **"Register application"**
5. Copy the **Client ID** and generate a **Client Secret**

#### Configure .env

Add to your `.env` file:

```env
GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret
GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback
```

---

### 2. Google OAuth Setup

#### Create Google OAuth App

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project (or select existing):
   - Click **"Select a Project"** → **"New Project"**
   - Name: `CareerOS`
   - Click **"Create"**

3. **Enable Google+ API**:
   - Navigate to **APIs & Services** → **Library**
   - Search for **"Google+ API"**
   - Click **Enable**

4. **Configure OAuth Consent Screen**:
   - Go to **APIs & Services** → **OAuth consent screen**
   - Choose **External** (for testing) or **Internal** (for workspace users)
   - Fill in required fields:
     - App name: `CareerOS`
     - User support email: your email
     - Developer contact: your email
   - Click **"Save and Continue"**
   - Skip **Scopes** → Click **"Save and Continue"**
   - Add test users (your email) → Click **"Save and Continue"**

5. **Create OAuth Credentials**:
   - Go to **APIs & Services** → **Credentials**
   - Click **"Create Credentials"** → **"OAuth 2.0 Client IDs"**
   - Application type: **Web application**
   - Name: `CareerOS Web Client`
   - **Authorized redirect URIs**:
     - `http://localhost:8000/auth/google/callback`
   - Click **"Create"**
   - Copy **Client ID** and **Client Secret**

#### Configure .env

Add to your `.env` file:

```env
GOOGLE_CLIENT_ID=your_google_client_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

---

## Production Configuration

### Update Callback URLs

When deploying to production (e.g., `https://careeros.app`):

#### GitHub OAuth App
1. Go to GitHub OAuth App settings
2. Update **Homepage URL**: `https://careeros.app`
3. Update **Authorization callback URL**: `https://careeros.app/auth/github/callback`

#### Google OAuth App
1. Go to Google Cloud Console → Credentials
2. Edit your OAuth 2.0 Client ID
3. Add authorized redirect URI: `https://careeros.app/auth/google/callback`
4. Update OAuth consent screen URLs

#### Update .env (Production)
```env
APP_URL=https://careeros.app

GITHUB_REDIRECT_URI=https://careeros.app/auth/github/callback
GOOGLE_REDIRECT_URI=https://careeros.app/auth/google/callback
```

---

## How It Works

### Authentication Flow

1. **User clicks social button** → Redirects to `/auth/{provider}/redirect`
2. **OAuth provider login** → User logs into GitHub/Google
3. **Permission grant** → User authorizes CareerOS access
4. **Callback** → Provider redirects to `/auth/{provider}/callback`
5. **User creation/login**:
   - If email exists → Log in existing user
   - If new email → Create new user account
6. **Redirect to dashboard** → User lands at `/applications`

### What Data is Stored?

#### GitHub Login
- `name` → GitHub username or display name
- `email` → GitHub email
- `github_username` → GitHub handle
- `github_token` → Access token (enables repo syncing)

#### Google Login
- `name` → Google display name
- `email` → Google email
- No additional fields stored

### Security Features

✅ **Email verified automatically** - OAuth providers verify email ownership  
✅ **Random password generated** - Users can still reset/set password later  
✅ **Token encryption** - GitHub tokens stored securely in database  
✅ **CSRF protection** - Laravel's built-in protection active  
✅ **HTTPS required in production** - OAuth requires secure callback URLs  

---

## Routes

| Method | Route | Description |
|--------|-------|-------------|
| `GET` | `/auth/{provider}/redirect` | Redirect to OAuth provider login |
| `GET` | `/auth/{provider}/callback` | Handle OAuth callback |

**Supported Providers**: `github`, `google`

---

## User Experience

### Login Page (`/login`)
- **GitHub button** → "Continue with GitHub"
- **Google button** → "Continue with Google"
- **Divider** → "or continue with email"
- Traditional email/password form below

### Register Page (`/register`)
- **GitHub button** → "Sign up with GitHub"
- **Google button** → "Sign up with Google"
- **Divider** → "or register with email"
- Traditional registration form below

### First-Time Login
- New user created automatically
- `is_profile_public` set to `false` (private by default)
- Redirected to applications dashboard
- Can enable public portfolio later in profile settings

---

## Troubleshooting

### "Invalid OAuth Token" Error
**Cause**: Client ID or Secret misconfigured  
**Fix**: Double-check `.env` credentials match OAuth app settings

### "Redirect URI Mismatch" Error
**Cause**: Callback URL in OAuth app doesn't match `.env`  
**Fix**: Ensure both use exact same URL (check http vs https, trailing slash)

### "This app is blocked" (Google)
**Cause**: OAuth consent screen not configured or app not published  
**Fix**: 
1. Add your email as test user in OAuth consent screen
2. For production, submit app for verification

### GitHub Token Not Saved
**Cause**: Scopes not requested  
**Fix**: Verify `SocialAuthController::getScopes()` includes `['read:user', 'repo']`

### User Can't Login After Registering via Social
**Cause**: No password set (expected behavior)  
**Fix**: User must use same social provider or reset password via email

---

## Extending Social Login

### Add More Providers

Laravel Socialite supports many providers. To add LinkedIn/Twitter/Facebook:

1. **Install provider** (if not included):
   ```bash
   composer require socialiteproviders/linkedin
   ```

2. **Add to config/services.php**:
   ```php
   'linkedin' => [
       'client_id' => env('LINKEDIN_CLIENT_ID'),
       'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
       'redirect' => env('LINKEDIN_REDIRECT_URI'),
   ],
   ```

3. **Update SocialAuthController**:
   - Add provider to `validateProvider()` array
   - Add scopes in `getScopes()` method

4. **Add button to login.blade.php**:
   ```blade
   <a href="{{ route('auth.social.redirect', 'linkedin') }}">
       Sign in with LinkedIn
   </a>
   ```

---

## Testing Social Login

### Manual Testing
1. Navigate to `http://localhost:8000/login`
2. Click "Continue with GitHub" or "Continue with Google"
3. Authorize the app
4. Verify redirect to `/applications`
5. Check database: `users` table should have new record

### Testing as Different Users
- GitHub: Use different GitHub accounts
- Google: Use Gmail accounts or Google Workspace accounts
- Use incognito/private browsing to test fresh sessions

---

## Best Practices

✅ **Use HTTPS in production** - Required for OAuth security  
✅ **Store tokens securely** - Never commit `.env` with real credentials  
✅ **Handle errors gracefully** - Show user-friendly error messages  
✅ **Limit token scopes** - Only request necessary permissions  
✅ **Regular token rotation** - Regenerate secrets periodically  
✅ **Monitor OAuth apps** - Check GitHub/Google dashboards for usage  

---

## Related Documentation

- [Laravel Socialite Docs](https://laravel.com/docs/11.x/socialite)
- [GitHub OAuth Apps](https://docs.github.com/en/apps/oauth-apps/building-oauth-apps)
- [Google OAuth 2.0](https://developers.google.com/identity/protocols/oauth2)
- [GITHUB_INTEGRATION.md](./GITHUB_INTEGRATION.md) - Repository syncing guide
