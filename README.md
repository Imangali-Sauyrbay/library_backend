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
DB_CONNECTION
DB_HOST
DB_PORT
DB_DB
DB_TEST_DB
DB_USER
DB_PASSWORD
```