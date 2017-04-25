#!/bin/sh

user=$1
pass=$2

if [[ "$user" != "" || "$pass" != "" ]]; then

USER="CREATE USER '$user'@'list.list-network' IDENTIFIED BY '$pass';
GRANT ALL PRIVLEGES on grList.* TO '$user'@list.list-network';
FLUSH PRIVLEGES;
REVOKE DROP ON grList.* FROM '$user'@'list.list-network';"

mysql -e "$USER"

else

echo "You need to set a username and password for the datbase user.  ex ./createDb.sh username password"
exit

fi

CREATE="create database grList;"

mysql -e "$CREATE"

TABLE="use grList;
create table grListANDItemsIntersection(
grListID INT,
itemId INT,
qty INT);
create table items(
itemId INT AUTO_INCREMENT,
item varchar(255),
measure varchar(255),
PRIMARY KEY (itemId));"

mysql -e "$TABLE"
