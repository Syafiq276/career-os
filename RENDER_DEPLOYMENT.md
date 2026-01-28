# 🚀 Deploying CareerOS to Render

This guide covers deploying your CareerOS Laravel application to Render's cloud platform.

## 📋 Prerequisites

1. **GitHub Account** - Your code must be in a GitHub repository
2. **Render Account** - Sign up at https://render.com (free tier available)
3. **Git Repository** - Push your CareerOS code to GitHub

## 🔧 Pre-Deployment Setup

### 1. Push Code to GitHub

```bash
cd "C:\xampp\htdocs\Job Hunter Dashboard\career-os"

# Initialize git if not already done
git init

# Add all files
git add .

# Commit
git commit -m "Initial commit - CareerOS ready for deployment"

# Add your GitHub repository as remote (replace with your repo URL)
git remote add origin https://github.com/YOUR_USERNAME/careeros.git

# Push to GitHub
git push -u origin main
```

### 2. Update Database Configuration

Since Render provides PostgreSQL (not MySQL), we need to ensure PostgreSQL support:

**Already configured in `config/database.php` - no changes needed!**

Laravel supports both MySQL (local) and PostgreSQL (production) out of the box.

## 🌐 Deploy to Render

### Method 1: Using Render Blueprint (Recommended)

1. **Go to Render Dashboard**: https://dashboard.render.com/

2. **Click "New +"** → **"Blueprint"**

3. **Connect Your GitHub Repository**
   - Authorize Render to access your GitHub
   - Select your CareerOS repository

4. **Render will auto-detect `render.yaml`**
   - Review the services (Web Service + PostgreSQL)
   - Click **"Apply"**

5. **Deployment starts automatically!**
   - Web service builds and deploys
   - PostgreSQL database is created
   - Migrations run automatically

6. **Access Your App**
   - Once deployment completes, click your service name
   - Copy the `.onrender.com` URL
   - Open it in your browser!

### Method 2: Manual Setup

If you prefer manual configuration:

#### Step 1: Create PostgreSQL Database

1. Go to Render Dashboard → **"New +"** → **"PostgreSQL"**
2. Configure:
   - **Name**: `careeros-db`
   - **Database**: `careeros`
   - **User**: `careeros_user`
   - **Plan**: Free
3. Click **"Create Database"**
4. **Save the connection details** shown

#### Step 2: Create Web Service

1. Go to Render Dashboard → **"New +"** → **"Web Service"**
2. Connect your GitHub repository
3. Configure:
   - **Name**: `careeros`
   - **Runtime**: `PHP`
   - **Build Command**: `./render-build.sh`
   - **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`
   - **Plan**: Free

#### Step 3: Configure Environment Variables

In the web service settings, add these environment variables:

```bash
APP_NAME=CareerOS
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com

# Generate this with: php artisan key:generate --show
APP_KEY=base64:YOUR_GENERATED_KEY_HERE

LOG_CHANNEL=stderr
LOG_LEVEL=error

# Database (from PostgreSQL connection info)
DB_CONNECTION=pgsql
DB_HOST=your-db-host.render.com
DB_PORT=5432
DB_DATABASE=careeros
DB_USERNAME=careeros_user
DB_PASSWORD=your_database_password

# Cache & Session
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Broadcasting
BROADCAST_CONNECTION=log

# Optional: Mail configuration (if needed)
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.mailtrap.io
# MAIL_PORT=2525
# MAIL_USERNAME=null
# MAIL_PASSWORD=null
# MAIL_ENCRYPTION=null
```

#### Step 4: Deploy

1. Click **"Create Web Service"**
2. Deployment starts automatically
3. Monitor build logs in the Render dashboard

## 🔄 Continuous Deployment

Render automatically redeploys when you push to GitHub:

```bash
# Make changes to your code
git add .
git commit -m "Update feature X"
git push origin main

# Render automatically rebuilds and redeploys!
```

## 🐛 Troubleshooting

### Build Fails

**Issue**: `render-build.sh: Permission denied`

**Solution**: Make script executable locally before pushing:
```bash
git update-index --chmod=+x render-build.sh
git commit -m "Make build script executable"
git push
```

### Database Connection Error

**Issue**: `SQLSTATE[08006] Could not connect to database`

**Solution**: 
1. Verify PostgreSQL database is running (Status: Available)
2. Check environment variables match database connection info
3. Ensure `DB_CONNECTION=pgsql` (not `mysql`)

### APP_KEY Missing

**Issue**: `No application encryption key has been specified`

**Solution**: Generate key locally and add to environment variables:
```bash
php artisan key:generate --show
# Copy the output (e.g., base64:abc123...)
# Add to Render environment variables as APP_KEY
```

### 500 Internal Server Error

**Solution**: Enable debug mode temporarily to see error:
1. Set `APP_DEBUG=true` in Render environment variables
2. Save and redeploy
3. Check logs: Dashboard → Your Service → Logs
4. Fix the issue, then set `APP_DEBUG=false` again

### Storage Permission Issues

**Solution**: Build script already handles this with `chmod -R 775 storage bootstrap/cache`

If issues persist:
```bash
# SSH into Render (if available on your plan)
chmod -R 777 storage bootstrap/cache
```

## 📊 Database Seeding (Optional)

To add demo data to production:

1. **Uncomment in `render-build.sh`**:
```bash
# Remove the # before this line:
php artisan db:seed --force
```

2. **Push changes**:
```bash
git add render-build.sh
git commit -m "Enable database seeding"
git push
```

3. **Or seed manually via Render Shell** (Paid plans only):
   - Dashboard → Your Service → Shell
   - Run: `php artisan db:seed --force`

## 🔒 Security Best Practices

### Production Environment Variables

Ensure these are set correctly:

```bash
APP_ENV=production           # Never use 'local' in production
APP_DEBUG=false              # Never true in production
APP_URL=https://your-actual-domain.onrender.com
```

### Database Backups

Render provides automatic daily backups on paid plans. For free tier:
1. Manually export: Dashboard → Database → Backups
2. Download SQL dump regularly

### HTTPS

✅ Render provides free SSL certificates automatically!

## 🎯 Post-Deployment

### Create Your First User

Visit your deployed app:
```
https://your-app-name.onrender.com/register
```

Register an account and start using CareerOS!

### Update Your Profile

1. Login to your account
2. Go to Profile settings
3. Add your GitHub username, LinkedIn, portfolio URL
4. The public portfolio will be at: `https://your-app.onrender.com/`

## 📈 Performance Tips

### Free Tier Limitations

- **Spin down after 15 min inactivity** - First request after inactivity takes ~30 seconds
- **750 hours/month** - Enough for demo/portfolio use
- **Limited CPU/RAM** - Good for small traffic

### Upgrade Options

For production traffic, consider upgrading:
- **Starter Plan ($7/mo)**: No spin down, more resources
- **PostgreSQL Paid**: Automated backups, more storage

### Caching Strategy

The build script already optimizes with:
- `php artisan config:cache` - Cache configuration
- `php artisan route:cache` - Cache routes
- `php artisan view:cache` - Precompile views

## 🔗 Custom Domain (Optional)

1. Purchase domain (e.g., from Namecheap, Google Domains)
2. In Render Dashboard → Your Service → Settings → Custom Domains
3. Add your domain (e.g., `careeros.yourdomain.com`)
4. Update DNS records at your domain registrar as shown
5. Update `APP_URL` environment variable

## 🎉 Success!

Your CareerOS is now live! Share your portfolio:

```
https://your-app-name.onrender.com
```

## 📞 Support

- **Render Docs**: https://render.com/docs
- **Laravel Docs**: https://laravel.com/docs
- **CareerOS Issues**: [Your GitHub repo]/issues

---

Built with ❤️ and { code }
