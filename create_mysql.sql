DROP DATABASE IF EXISTS scheduling;
CREATE DATABASE scheduling;
USE scheduling;

CREATE TABLE schedule_blocks
(
	block_id SMALLINT(3) NOT NULL AUTO_INCREMENT,
	time_descr VARCHAR(60) NOT NULL,
	instructor VARCHAR(20) NOT NULL,
	active TINYINT(1) NOT NULL DEFAULT 1,
	PRIMARY KEY(block_id)
);

CREATE TABLE vote_sessions
(
	vote_session_id MEDIUMINT(7) NOT NULL AUTO_INCREMENT,
	login VARCHAR(15) NOT NULL,
	voted_on DATETIME NOT NULL,
	active TINYINT(1) NOT NULL DEFAULT 1,
	PRIMARY KEY(vote_session_id)
);

CREATE TABLE bids
(
	bid_id SMALLINT(5) NOT NULL AUTO_INCREMENT,
	vote_session_id MEDIUMINT(7) NOT NULL,
	block_id SMALLINT(3) NOT NULL,
	num_schillings SMALLINT(4) NOT NULL,
	PRIMARY KEY(bid_id),
	FOREIGN KEY(vote_session_id) REFERENCES vote_sessions(vote_session_id),
	FOREIGN KEY(block_id) REFERENCES schedule_blocks(block_id)
);

CREATE TABLE vetos
(
	veto_id SMALLINT(5) NOT NULL AUTO_INCREMENT,
	vote_session_id MEDIUMINT(7) NOT NULL,
	block_id SMALLINT(3) NOT NULL,
	PRIMARY KEY(veto_id),
	FOREIGN KEY(vote_session_id) REFERENCES vote_sessions(vote_session_id),
	FOREIGN KEY(block_id) REFERENCES schedule_blocks(block_id)
);

CREATE TABLE users
(
	login VARCHAR(15) NOT NULL,
	admin SMALLINT(1) NOT NULL DEFAULT 0,
	PRIMARY KEY(login)
);

INSERT INTO users (login, admin) VALUES ('mchow', 1);
INSERT INTO users (login, admin) VALUES ('nr', 1);

INSERT INTO schedule_blocks (time_descr, instructor) VALUES ('2:30-3:45 Friday', 'Taft');
INSERT INTO schedule_blocks (time_descr, instructor) VALUES ('3:00-4:15 Wednesday', 'Taft');
INSERT INTO schedule_blocks (time_descr, instructor) VALUES ('10:30-11:45 Wednesday', 'McKeeman');
INSERT INTO schedule_blocks (time_descr, instructor) VALUES ('10:30-11:45 Friday', 'McKeeman');
INSERT INTO schedule_blocks (time_descr, instructor) VALUES ('4:30-5:45 Monday Wednesday', 'Rieker');
INSERT INTO schedule_blocks (time_descr, instructor) VALUES ('4:30-5:45 Tuesday Thursday', 'Rieker');
INSERT INTO schedule_blocks (time_descr, instructor) VALUES ('4:30-5:45 Friday', 'Rieker');
INSERT INTO schedule_blocks (time_descr, instructor) VALUES ('3:00-4:15 Monday Wednesday', 'Rieker');
INSERT INTO schedule_blocks (time_descr, instructor) VALUES ('3:00-4:15 Tuesday Thursday', 'Rieker');
INSERT INTO schedule_blocks (time_descr, instructor) VALUES ('3:00-4:15 Friday', 'Rieker');
INSERT INTO schedule_blocks (time_descr, instructor) VALUES ('7:30-8:45 Monday Wednesday', 'Rutman');
INSERT INTO schedule_blocks (time_descr, instructor) VALUES ('7:30-8:45 Tuesday Thursday', 'Rutman');
