CREATE DATABASE IF NOT EXISTS busriya_v1;
USE busriya_v1;

CREATE TABLE IF NOT EXISTS tbl_route (
	routeId 			INT(8) 			NOT NULL 	AUTO_INCREMENT,
	route_no			VARCHAR(6)		NOT NULL,
	start_loc			VARCHAR(100)	NOT NULL,
	end_loc				VARCHAR(100)	NOT NULL,
	
	PRIMARY KEY (routeId)
);

CREATE TABLE IF NOT EXISTS tbl_administrator (
	nic					VARCHAR(10)		NOT NULL,
	fname				VARCHAR(20)		NOT NULL,
	lname				VARCHAR(20)		NOT NULL,
	tel					INT(10)			NOT NULL,
	addr1				VARCHAR(30)		NOT NULL,
	addr2				VARCHAR(30)		NOT NULL,
	
	PRIMARY KEY (nic)	
);
CREATE TABLE IF NOT EXISTS admin_login (
	username			VARCHAR(12)		NOT NULL,
	nic					VARCHAR(10)		NOT NULL,
	password			VARCHAR(20)		NOT NULL,
	email				VARCHAR(50)		NOT NULL,	
	
	PRIMARY KEY (username),
	FOREIGN KEY (nic) REFERENCES  tbl_administrator (nic)
);
CREATE TABLE IF NOT EXISTS tbl_tokens_admin(
	token				VARCHAR(32)		NOT NULL,
	username			VARCHAR(12)		NOT NULL,
	timeCreated			DATETIME 		NOT NULL,
	refreshToken		VARCHAR(32)		NOT NULL,

	PRIMARY KEY(token, username),
	FOREIGN KEY (username) REFERENCES admin_login (username)
);
CREATE TABLE IF NOT EXISTS admin_login_history (
	adminHisId			INT				NOT NULL 	AUTO_INCREMENT,
	nic					VARCHAR(10)		NOT NULL,
	login_date_time		DATETIME 		NOT NULL,
	logout_date_time	DATETIME 		NOT NULL,
	
	PRIMARY KEY (adminHisId, nic),
	FOREIGN KEY (nic) REFERENCES  tbl_administrator (nic)
);

CREATE TABLE IF NOT EXISTS client (
	client_id			INT				NOT NULL 	AUTO_INCREMENT,
	fname				VARCHAR(10)		NOT NULL,
	lname				VARCHAR(20)		NOT NULL,
	tel					VARCHAR(50)		NOT NULL,	
	
	PRIMARY KEY (client_id)
);
CREATE TABLE IF NOT EXISTS client_login (
	username			VARCHAR(10)		NOT NULL,
	client_id			INT				NOT NULL 	AUTO_INCREMENT,
	password			VARCHAR(20)		NOT NULL,
	email				VARCHAR(50)		NOT NULL,	
	
	PRIMARY KEY (username, client_id),
	FOREIGN KEY (client_id) REFERENCES client (client_id)
);
CREATE TABLE IF NOT EXISTS tbl_tokens_client(
	token				VARCHAR(32)		NOT NULL,
	username			VARCHAR(10)		NOT NULL,
	timeCreated			DATETIME 		NOT NULL,
	refreshToken		VARCHAR(32)		NOT NULL,

	PRIMARY KEY(token, username),
	FOREIGN KEY (username) REFERENCES client_login (username)
);

CREATE TABLE IF NOT EXISTS busOwner(
	licenseNo			VARCHAR(20)		NOT NULL,
	fname				VARCHAR(32)		NOT NULL,
	lname				VARCHAR(32)		NOT NULL,
	addr1				VARCHAR(32)		NOT NULL,
	addr2				VARCHAR(32)		NOT NULL,
	tel					INT(10)			NOT NULL,

	PRIMARY KEY(licenseNo)
);
CREATE TABLE IF NOT EXISTS bus(
	bus_no				VARCHAR(32)		NOT NULL,
	licenseNo			VARCHAR(32)		NOT NULL,
	routeId				INT(8)			NOT NULL,
	bus_type			VARCHAR(32)		NOT NULL,
	cur_seat_cap		VARCHAR(32)		NOT NULL DEFAULT 0,
	seat_cap			VARCHAR(32)		NOT NULL,

	PRIMARY KEY (bus_no, licenseNo),
	FOREIGN KEY (licenseNo) REFERENCES busOwner (licenseNo),
	FOREIGN KEY (routeId) REFERENCES tbl_route (routeId)
);
CREATE TABLE IF NOT EXISTS bus_history(
	bus_hdId			INT				NOT NULL,
	bus_no				VARCHAR(20)		NOT NULL,
	start_time			VARCHAR(32)		NOT NULL,
	end_time			VARCHAR(32)		NOT NULL,

	PRIMARY KEY (bus_hdId),
	FOREIGN KEY (bus_no) REFERENCES bus (bus_no)
);

CREATE TABLE IF NOT EXISTS location(
	loc_id				INT				NOT NULL 	AUTO_INCREMENT,
	bus_no				VARCHAR(20)		NOT NULL,
	cur_lon				VARCHAR(32)		NOT NULL,
	cur_lat				VARCHAR(32)		NOT NULL,
	returnData 			VARCHAR(1) 		NOT NULL,

	PRIMARY KEY(loc_id),
	FOREIGN KEY (bus_no) REFERENCES bus (bus_no)
);

CREATE TABLE IF NOT EXISTS complaint(
	complain_id			INT				NOT NULL 	AUTO_INCREMENT,
	client_id			INT				NOT NULL,
	bus_no				VARCHAR(32)		NOT NULL,
	date_time			VARCHAR(32)		NOT NULL,
	complain_text 		VARCHAR(250) 		NOT NULL,

	PRIMARY KEY (complain_id),
	FOREIGN KEY (client_id) REFERENCES client (client_id),
	FOREIGN KEY (bus_no) REFERENCES bus (bus_no)
);