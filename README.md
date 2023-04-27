### For Loacal dev:
1) Copy .env.example:
```
cp .env.example .env
```
2) Add user and group id to .env:
```
echo UID=$(id -u) >> .env && echo GID=$(id -g) >> .env
```
3) Run Docker services:
```
docker-compose up -d --build
```

### For prod container:
Env variables:
```
APP_KEY
APP_URL
SESSION_DOMAIN



QUEUE_CONNECTION=redis/sync

SESSION_DRIVER=redis /file
SESSION_CONNECTION=session /null
SESSION_LIFETIME=120

CACHE_DRIVER=redis /file
FILESYSTEM_DISK=local

REDIS_HOST
REDIS_PASSWORD
REDIS_PORT

SCOUT_DRIVER
MEILISEARCH_HOST
MEILISEARCH_KEY
```