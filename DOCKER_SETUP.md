# 🐳 Docker Setup Guide - CareerOS

Run CareerOS in a fully containerized environment with MySQL, Redis, and optimized PHP-FPM + Nginx stack.

---

## 🚀 Quick Start

### Prerequisites

- **Docker Desktop** installed ([Download](https://www.docker.com/products/docker-desktop))
- **Docker Compose** v2.0+ (included with Docker Desktop)
- **Git** (to clone the repository)

### 1. Build and Start Containers

```bash
# Navigate to project directory
cd "C:\xampp\htdocs\Job Hunter Dashboard\career-os"

# Copy Docker environment file
copy .env.docker .env

# Generate application key
docker-compose run --rm app php artisan key:generate

# Build and start all services
docker-compose up -d --build
```

### 2. Initialize Database

```bash
# Wait 10 seconds for MySQL to fully start
timeout /t 10

# Run migrations
docker-compose exec app php artisan migrate --force

# Seed demo data (optional)
docker-compose exec app php artisan db:seed --force
```

### 3. Access CareerOS

Open your browser and navigate to:

**🌐 http://localhost:8080**

**Default Credentials:**
- Email: `demo@jobhunter.test`
- Password: `password`

---

## 📦 Container Architecture

### Services

| Service | Container | Port | Description |
|---------|-----------|------|-------------|
| **app** | `careeros-app` | 8080 | Laravel 11 + PHP 8.2 + Nginx |
| **db** | `careeros-db` | 3307 | MySQL 8.0 Database |
| **redis** | `careeros-redis` | 6380 | Redis 7 Cache & Sessions |

### Volumes

- `db-data` - Persistent MySQL database storage
- `redis-data` - Persistent Redis cache storage
- `.` - Live code sync (hot reload on file changes)

---

## 🛠️ Common Commands

### Container Management

```bash
# Start all containers
docker-compose up -d

# Stop all containers
docker-compose down

# View logs
docker-compose logs -f app

# Restart specific service
docker-compose restart app

# Rebuild containers (after Dockerfile changes)
docker-compose up -d --build
```

### Laravel Artisan Commands

```bash
# Run migrations
docker-compose exec app php artisan migrate

# Create new migration
docker-compose exec app php artisan make:migration create_table_name

# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Run queue worker
docker-compose exec app php artisan queue:work

# Run tinker (REPL)
docker-compose exec app php artisan tinker
```

### Database Access

```bash
# Access MySQL CLI
docker-compose exec db mysql -u careeros_user -pcareeros_password careeros

# Export database
docker-compose exec db mysqldump -u careeros_user -pcareeros_password careeros > backup.sql

# Import database
docker-compose exec -T db mysql -u careeros_user -pcareeros_password careeros < backup.sql
```

### Redis Access

```bash
# Access Redis CLI
docker-compose exec redis redis-cli

# Flush all cache
docker-compose exec redis redis-cli FLUSHALL
```

### Composer & Dependencies

```bash
# Install PHP packages
docker-compose exec app composer install

# Add new package
docker-compose exec app composer require vendor/package

# Update dependencies
docker-compose exec app composer update
```

---

## 🔧 Configuration

### Environment Variables

Edit `.env` file to customize:

```env
# Application
APP_ENV=production
APP_DEBUG=false  # Set to true for debugging
APP_URL=http://localhost:8080

# Database
DB_HOST=db
DB_DATABASE=careeros
DB_USERNAME=careeros_user
DB_PASSWORD=careeros_password

# Cache & Sessions
CACHE_STORE=redis
SESSION_DRIVER=redis
REDIS_HOST=redis

# OAuth (update with your credentials)
GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
```

### Port Configuration

Change ports in `docker-compose.yml`:

```yaml
services:
  app:
    ports:
      - "8080:80"  # Change 8080 to your preferred port
  db:
    ports:
      - "3307:3306"  # MySQL external port
  redis:
    ports:
      - "6380:6379"  # Redis external port
```

### PHP Settings

Modify `docker/php/local.ini`:

```ini
upload_max_filesize = 40M
memory_limit = 256M
max_execution_time = 300
```

### Nginx Settings

Edit `docker/nginx/default.conf` for custom server configuration.

---

## 🐛 Troubleshooting

### Port Already in Use

**Error:** `Bind for 0.0.0.0:8080 failed: port is already allocated`

**Fix:** Change port in `docker-compose.yml`:
```yaml
ports:
  - "8081:80"  # Use 8081 instead
```

### Database Connection Failed

**Error:** `SQLSTATE[HY000] [2002] Connection refused`

**Fix:** Wait for MySQL to fully initialize:
```bash
# Check MySQL health
docker-compose logs db

# Restart containers
docker-compose restart app
```

### Permission Denied (Windows)

**Error:** `Permission denied` when writing to storage/logs

**Fix:** Run Docker Desktop as Administrator or adjust file sharing settings:
1. Docker Desktop → Settings → Resources → File Sharing
2. Add project directory path
3. Restart Docker Desktop

### 500 Internal Server Error

**Common causes:**
1. **APP_KEY not set**: Run `docker-compose exec app php artisan key:generate`
2. **Storage permissions**: 
   ```bash
   docker-compose exec app chmod -R 775 storage bootstrap/cache
   docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
   ```
3. **Database not migrated**: Run `docker-compose exec app php artisan migrate`

### Container Won't Start

**Check logs:**
```bash
docker-compose logs app
docker-compose logs db
docker-compose logs redis
```

**Common fixes:**
```bash
# Remove old containers and volumes
docker-compose down -v

# Rebuild from scratch
docker-compose up -d --build --force-recreate
```

---

## 🚢 Production Deployment

### Build Production Image

```bash
# Build optimized image
docker build -t careeros:latest .

# Tag for registry
docker tag careeros:latest your-registry.com/careeros:latest

# Push to registry
docker push your-registry.com/careeros:latest
```

### Production Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate secure `APP_KEY`
- [ ] Use strong database passwords
- [ ] Enable HTTPS with SSL certificates
- [ ] Configure proper OAuth callback URLs
- [ ] Set up automated backups
- [ ] Configure logging and monitoring
- [ ] Enable OPcache (already configured in `local.ini`)
- [ ] Use external Redis for distributed caching
- [ ] Set up health checks and auto-restart policies

### Environment-Specific Configurations

**Staging:**
```env
APP_ENV=staging
APP_DEBUG=true
APP_URL=https://staging.careeros.app
```

**Production:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://careeros.app
LOG_LEVEL=warning  # Reduce log verbosity
```

---

## 📊 Performance Optimization

### Caching in Docker

```bash
# Cache routes
docker-compose exec app php artisan route:cache

# Cache configuration
docker-compose exec app php artisan config:cache

# Cache views
docker-compose exec app php artisan view:cache

# Optimize autoloader
docker-compose exec app composer dump-autoload --optimize
```

### Database Optimization

```bash
# Optimize tables
docker-compose exec db mysql -u careeros_user -pcareeros_password careeros -e "OPTIMIZE TABLE users, projects, skills, applications;"

# Analyze query performance
docker-compose exec db mysql -u careeros_user -pcareeros_password careeros -e "SHOW PROCESSLIST;"
```

### Redis Monitoring

```bash
# Check Redis info
docker-compose exec redis redis-cli INFO

# Monitor commands in real-time
docker-compose exec redis redis-cli MONITOR
```

---

## 🔒 Security Best Practices

### Change Default Credentials

Update `.env`:
```env
DB_PASSWORD=your_strong_password_here
MYSQL_ROOT_PASSWORD=your_root_password_here
```

Update `docker-compose.yml`:
```yaml
db:
  environment:
    MYSQL_PASSWORD: your_strong_password_here
    MYSQL_ROOT_PASSWORD: your_root_password_here
```

### Firewall Rules

Block direct database access:
```bash
# Only allow localhost connections
ports:
  - "127.0.0.1:3307:3306"
  - "127.0.0.1:6380:6379"
```

### Enable HTTPS

For production, use reverse proxy (Traefik, Nginx Proxy Manager):
```yaml
labels:
  - "traefik.enable=true"
  - "traefik.http.routers.careeros.rule=Host(`careeros.app`)"
  - "traefik.http.routers.careeros.tls=true"
  - "traefik.http.routers.careeros.tls.certresolver=letsencrypt"
```

---

## 📝 Maintenance

### Backup Database

```bash
# Create backup
docker-compose exec db mysqldump -u careeros_user -pcareeros_password careeros > backup_$(date +%Y%m%d).sql

# Compress backup
gzip backup_$(date +%Y%m%d).sql
```

### Restore Database

```bash
# Restore from backup
docker-compose exec -T db mysql -u careeros_user -pcareeros_password careeros < backup_20260128.sql
```

### Update CareerOS

```bash
# Pull latest code
git pull origin main

# Rebuild containers
docker-compose up -d --build

# Run new migrations
docker-compose exec app php artisan migrate --force

# Clear caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
```

### Clean Up

```bash
# Remove stopped containers
docker-compose down

# Remove with volumes (CAUTION: deletes data)
docker-compose down -v

# Remove unused images
docker image prune -a

# Remove build cache
docker builder prune
```

---

## 🆘 Support

### Check Container Status

```bash
docker-compose ps
```

### View Resource Usage

```bash
docker stats
```

### Access Container Shell

```bash
# App container
docker-compose exec app bash

# Database container
docker-compose exec db bash

# Redis container
docker-compose exec redis sh
```

### Export Logs

```bash
docker-compose logs app > app.log
docker-compose logs db > db.log
docker-compose logs redis > redis.log
```

---

## 🎯 Next Steps

1. ✅ Configure OAuth credentials in `.env`
2. ✅ Customize GitHub Integration settings
3. ✅ Set up automated backups
4. ✅ Configure monitoring and alerting
5. ✅ Enable HTTPS for production
6. ✅ Set up CI/CD pipeline

**Happy containerizing! 🚀**
