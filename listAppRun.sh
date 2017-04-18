docker network create -d bridge list-network
docker run -d --name listDb --network="list-network" -e MYSQL_ROOT_PASSWORD=July,252014 -e MYSQL_DATABASE=grList mysql:8
docker run -d --name list --network="list-network" -p 8082:80 -v "$PWD":/var/www/localhost/htdocs/list shavez00/alpine-apache2-php:withPdoForMysqlSupport
