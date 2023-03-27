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