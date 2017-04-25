#!/bin/sh

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
