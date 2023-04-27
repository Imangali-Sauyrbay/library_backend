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

DB_CONNECTION
DB_HOST
DB_PORT
DB_DATABASE
DB_DATABASE_TEST
DB_USERNAME
DB_PASSWORD

REDIS_HOST
REDIS_PASSWORD
REDIS_PORT
```