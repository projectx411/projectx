
#DO NOT USE! EVER!! ^_^
/*
DROP table Student;
DROP table Does;
DROP table Activity;
DROP table Located;
DROP table Location;
*/

# create the database
create database if not exists projectx; use projectx;

# create the tables
create table Student (
		email			CHAR(255),
		password		CHAR(255),
		name			CHAR(255),
		gender			CHAR(255),
		phoneNumber		CHAR(255),
		PRIMARY KEY (email)
	);

create table Does (
		idDoes			INT NOT NULL AUTO_INCREMENT,
		email			char(255),
		idActivity		INT,
		PRIMARY KEY (idDoes)
	);

create table Activity (
		idActivity		INT NOT NULL AUTO_INCREMENT,
		activityName	char(255),
		categoryName	char(255),
		PRIMARY KEY (idActivity)
	);
ALTER TABLE Activity ADD UNIQUE INDEX(activityName, categoryName);

create table Located (
		idLocated		INT NOT NULL AUTO_INCREMENT,
		idActivity		INT,
		idLocation		INT,
		PRIMARY KEY (idLocated)
	);

create table Location (
		idLocation		INT NOT NULL AUTO_INCREMENT,
		locationName	char(255),
		address			char(255),
		PRIMARY KEY (idLocation)
	);