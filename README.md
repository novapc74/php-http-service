### Http microservice
```bash
$ make create_network
$ make create_shared_network
$ make init
$ make php-cli
```

#### require .env
```dotenv
APP_NAME=microserice

DATABASE_PORT=3306
DATABASE_HOST=${APP_NAME}-db
DATABASE_USER=dev
DATABASE_PASSWORD=dev
DATABASE_NAME=dev
```
