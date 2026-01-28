# CareerOS Scalability Guide

## Overview
This document outlines the scalability improvements implemented in CareerOS to support growth from prototype to production.

---

## 1. Database Optimization

### Indexes Added
All frequently queried columns now have indexes for faster lookups:

**Applications Table:**
- Composite index: `(user_id, status)` - Filter applications by user and status
- Composite index: `(user_id, applied_at)` - Sort user's applications by date
- Single indexes: `company_name`, `job_title` - Full-text search

**Projects Table:**
- Composite index: `(user_id, is_featured)` - Fetch featured projects
- Single index: `difficulty` - Filter by quest difficulty

**Skills Table:**
- Composite index: `(user_id, category)` - Group skills by category
- Single index: `score` - Filter by proficiency level

**Users Table:**
- Single index: `name` - Portfolio username lookups
- Single index: `is_profile_public` - Filter public profiles

### Query Scopes
Reusable query scopes reduce code duplication and improve maintainability:

```php
// Application model scopes
Application::active()->recent()->paginate(15);
Application::status('interview')->search('Google')->get();

// User model scopes
User::public()->with('projects', 'skills_data')->get();
```

---

## 2. N+1 Query Prevention

### Eager Loading
Controllers now use `with()` to load relationships in a single query:

```php
// Before: 1 + N queries (1 user + N projects)
$user = User::find(1);
foreach ($user->projects as $project) { ... }

// After: 2 queries total (1 user + 1 for all projects)
$user = User::with('projects')->find(1);
foreach ($user->projects as $project) { ... }
```

### Selective Column Loading
Only fetch needed columns to reduce memory and network transfer:

```php
User::with(['projects' => function($query) {
    $query->select('id', 'user_id', 'title', 'description', 'xp_gained');
}])->first();
```

---

## 3. Configuration Management

### Environment Variables
Use `.env` for environment-specific settings:

```env
# Development
DB_CONNECTION=sqlite
CAREEROS_QUERY_CACHE=false

# Production
DB_CONNECTION=mysql
CAREEROS_QUERY_CACHE=true
CAREEROS_PORTFOLIO_CACHE=120
```

### Config File: `config/careeros.php`
Centralized configuration for:
- XP system thresholds
- Pagination limits
- Cache TTL values
- Feature flags
- Performance toggles

---

## 4. Horizontal Scaling Readiness

### Stateless Design
- Sessions stored in database (can migrate to Redis)
- No file-based state dependencies
- Auth tokens support API access

### Database Migration Path
```
Development: SQLite (local, fast)
↓
Staging: MySQL (cloud-ready)
↓
Production: PostgreSQL + Read Replicas
```

### Cache Layer (Future)
```php
// Portfolio data caching example
Cache::remember('portfolio.demo', 3600, function () {
    return User::with('projects', 'skills')->where('name', 'demo')->first();
});
```

---

## 5. Performance Best Practices

### Pagination
All list endpoints use pagination (default: 15 items):
```php
$applications = $query->paginate(config('careeros.pagination.applications_per_page'));
```

### Lazy Loading Prevention
Use `$with` property or explicit `with()` calls:
```php
protected $with = ['user']; // Always load relationship
```

### Query Optimization Checklist
- ✅ Indexes on foreign keys and search columns
- ✅ Eager loading for relationships
- ✅ Select only needed columns
- ✅ Use query scopes for complex filters
- ✅ Pagination for large datasets

---

## 6. Scalability Roadmap

### Phase 1: Optimization (Completed)
- ✅ Database indexes
- ✅ Query scopes
- ✅ Eager loading
- ✅ Config management

### Phase 2: Caching (Next)
- [ ] Redis for sessions
- [ ] Query result caching
- [ ] Portfolio page caching
- [ ] CDN for static assets

### Phase 3: API & Microservices
- [ ] RESTful API endpoints
- [ ] Rate limiting
- [ ] API documentation (Swagger)
- [ ] Queue workers for async tasks

### Phase 4: Infrastructure
- [ ] Load balancer setup
- [ ] Database read replicas
- [ ] Full-text search (Elasticsearch)
- [ ] Monitoring & alerting

---

## 7. Load Testing Targets

### Current Capacity (Single Server)
- Concurrent users: ~100
- Requests per second: ~50
- Database queries: <50ms average

### Target Capacity (Optimized)
- Concurrent users: 1,000+
- Requests per second: 500+
- Database queries: <10ms average

---

## 8. Monitoring

### Key Metrics to Track
1. **Response Time**: Average page load (<200ms goal)
2. **Database Queries**: Number per request (<10 ideal)
3. **Memory Usage**: PHP process memory
4. **Cache Hit Rate**: Percentage of cached responses
5. **Error Rate**: 5xx errors (<0.1% goal)

### Tools Recommended
- Laravel Telescope (local development)
- New Relic / DataDog (production APM)
- MySQL Slow Query Log
- Redis Monitor

---

## 9. Code Quality

### Performance Patterns Used
```php
// Good: Single query with eager loading
$users = User::with('projects', 'skills_data')->public()->get();

// Bad: N+1 queries
$users = User::all();
foreach ($users as $user) {
    $user->projects; // Separate query each time
}
```

### Avoid Common Pitfalls
- ❌ Don't use `all()` without pagination
- ❌ Don't load relationships in loops
- ❌ Don't forget to index foreign keys
- ✅ Use chunking for large datasets
- ✅ Profile queries with `DB::listen()`

---

## 10. Deployment Checklist

### Before Going Live
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure queue workers
- [ ] Set up backup strategy
- [ ] Enable HTTPS
- [ ] Configure Redis/Memcached
- [ ] Set up monitoring tools
- [ ] Load test with realistic data

---

## Resources
- [Laravel Performance Best Practices](https://laravel.com/docs/performance)
- [Database Query Optimization](https://laravel.com/docs/queries#optimizing-queries)
- [Scaling Laravel Applications](https://laravel.com/docs/deployment)
