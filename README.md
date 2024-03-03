Test project
=================

Installing the project:
-----------------------
This command will build the containers, will create the db and start the containers as well.

```make build```

Running the tests:
-----------------------
*FIXME:* Docker db-init.sql is not working properly so running the test suit you need manually create and add privilages to *symfony_test* db. 

```CREATE DATABASE IF NOT EXISTS `symfony_test`;
GRANT ALL PRIVILEGES ON `symfony_test`.* TO 'user'@'%' IDENTIFIED BY 'PASSWORD';
FLUSH PRIVILEGES;```

After that you can run:
```make tests```

Urls:
-----------------------
- API: http://localhost:8000
- Swagger: http://localhost:8001/
- Dozzle: http://localhost:8002/
- Adminer: http://localhost:8003/