#the line below captures the first option entered "$1" and assigns it to the "port" varible
port=$1

#this checks if the varible "port" is non-zero
if [[ "$port" -gt 0 ]]; then
  
docker network create -d bridge list-network
docker run -d --name listDb --network="list-network" -v "$PWD":/var/list/mysql shavez00/alpine-mysql
#assigns the varible "port" to the first option of the docker run flag "-p"
docker run -d --name list --network="list-network" -p $port:80 -v "$PWD":/var/www/localhost/htdocs/list shavez00/alpine-apache2-php:withPdoForMysqlSupport

else

echo "You need to specify a port that you want the Grocery List app to run on, the port number needs to be a integer.  Example ./listAppRun.sh 8080"

fi
