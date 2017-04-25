#the line below captures the first option entered "$1" and assigns it to the "port" varible
port=$1
user=$2
pass=$3

#this checks if the varible "port" is non-zero
if [[ "$port" -gt 0 ]]; then

#create the docker bridge network that the database and webserver will run on
docker network create -d bridge list-network
#create the mysql server
docker run -d --name listDb --network="list-network" -v "$PWD":/var/list/mysql shavez00/alpine-mysql

#assigns the varible "port" to the first option of the docker run flag "-p"
docker run -d --name list --network="list-network" -p $port:80 -v "$PWD":/var/www/localhost/htdocs/list shavez00/alpine-apache2-php:withPdoForMysqlSupport

else

echo "You need to specify a port that you want the Grocery List app to run on, the port number needs to be a integer.  Example ./listAppRun.sh 8080"

fi

if [[ "$user" != "" && "$pass" != "" ]]; then
#create the database and tables needed
docker exec -it listDb /var/lib/mysql/createDb.sh $user $pass

else

echo "[ERORR}You need to enter in a username and password for the database user/n
Exiting list app and removing containers"
docker stop list
docker stop listDb
docker rm list
docker rm listDb
docker network rm list-network

fi
