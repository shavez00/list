#the line below captures the first option entered "$1" and assigns it to the "port" varible
port=$1
user=$2
pass=$3

#this checks if the varible "port" is non-zero
if [[ "$port" -gt 0 ]]; then

#create the docker bridge network that the database and webserver will run on
docker network create -d bridge list-network > /dev/null
echo "List App container bridge network up!"
#create the mysql server
docker run -d --name listDb --network="list-network" -v "$PWD":/var/lib/mysql shavez00/alpine-mysql . /dev/null
echo "List Add database listDb up!"

#assigns the varible "port" to the first option of the docker run flag "-p"
docker run -d --name list --network="list-network" -p $port:80 -v "$PWD":/var/www/localhost/htdocs/list shavez00/alpine-apache2-php:withPdoForMysqlSupport > /dev/null
echo "List App webserver 'list' up!"

else

echo "You need to specify a port that you want the Grocery List app to run on, the port number needs to be a integer.  Example ./listAppRun.sh 8080"
exit

fi

if [[ "$user" != "" && "$pass" != "" ]]; then
#create the database and tables needed
docker exec -it listDb sh /var/lib/mysql/createDb.sh $user $pass

#update dbenv.php file
mv dbenv.php.bak dbenv.php

echo "Database has been initalized and List App is ready to use!"

else

echo "[ERROR]You need to enter in a username and password for the database user
Exiting list app and removing containers
Please be patient, cleaning up..."
docker stop list > /dev/null
echo "Cleaning up..."
docker stop listDb > /dev/null
docker rm list > /dev/null
echo "Almost done..."
docker rm listDb > /dev/null
docker network rm list-network > /dev/null
echo "Clean up complete, all list app containers removed"
echo "Please use the following format.  ex ./listAppRun.sh 8080 DBusername DBpassword"

fi
