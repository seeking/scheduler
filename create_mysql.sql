/*DROP DATABASE IF EXISTS scheduling;
CREATE DATABASE scheduling;
USE scheduling;*/

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS vetos;
DROP TABLE IF EXISTS bids;
DROP TABLE IF EXISTS vote_sessions;
DROP TABLE IF EXISTS schedule_times;
DROP TABLE IF EXISTS schedule_blocks;

/* The list of scheduling times that people can vote on */

CREATE TABLE schedule_blocks
(
	block_id SMALLINT(3) NOT NULL,
	PRIMARY KEY(block_id)
);

CREATE TABLE schedule_times
(
	time_id SMALLINT(3) NOT NULL,
	block_id SMALLINT(3) NOT NULL,
	time_descr VARCHAR(60) NOT NULL,
	instructor VARCHAR(20) NOT NULL,
	PRIMARY KEY(time_id),
	FOREIGN KEY(block_id) REFERENCES schedule_blocks(block_id)
);

/* Every time a person votes, it gets logged; person can vote more than once but only last vote counts */
CREATE TABLE vote_sessions
(
	vote_session_id MEDIUMINT(7) NOT NULL AUTO_INCREMENT,
	login VARCHAR(60) NOT NULL,
	voted_on DATETIME NOT NULL,
	active TINYINT(1) NOT NULL DEFAULT 1,
	PRIMARY KEY(vote_session_id)
);

/* All the bids per person per session */
CREATE TABLE bids
(
	bid_id SMALLINT(5) NOT NULL AUTO_INCREMENT,
	vote_session_id MEDIUMINT(7) NOT NULL,
	time_id SMALLINT(3) NOT NULL,
	num_shillings SMALLINT(4) NOT NULL,
	PRIMARY KEY(bid_id),
	FOREIGN KEY(vote_session_id) REFERENCES vote_sessions(vote_session_id),
	FOREIGN KEY(time_id) REFERENCES schedule_times(time_id)
);

/* All the vetos per person per session */
CREATE TABLE vetos
(
	veto_id SMALLINT(5) NOT NULL AUTO_INCREMENT,
	vote_session_id MEDIUMINT(7) NOT NULL,
	time_id SMALLINT(3) NOT NULL,
	PRIMARY KEY(veto_id),
	FOREIGN KEY(vote_session_id) REFERENCES vote_sessions(vote_session_id),
	FOREIGN KEY(time_id) REFERENCES schedule_times(time_id)
);

/* Self-explanatory */
CREATE TABLE users
(
	login VARCHAR(60) NOT NULL,
	password TEXT NOT NULL,
	admin SMALLINT(1) NOT NULL DEFAULT 0,
	PRIMARY KEY(login)
);

/* Prepopulation */
INSERT INTO users (login, password, admin) VALUES ('mchow@cs.tufts.edu', SHA1('rexryanlovesfeet'), 1);
INSERT INTO users (login, password, admin) VALUES ('nr@cs.tufts.edu', SHA1('rexryanlovesfeet'), 1);
INSERT INTO schedule_blocks (block_id) VALUES (1);
INSERT INTO schedule_blocks (block_id) VALUES (2);
INSERT INTO schedule_blocks (block_id) VALUES (3);
INSERT INTO schedule_blocks (block_id) VALUES (4);
INSERT INTO schedule_blocks (block_id) VALUES (5);
INSERT INTO schedule_blocks (block_id) VALUES (6);
INSERT INTO schedule_blocks (block_id) VALUES (7);
INSERT INTO schedule_blocks (block_id) VALUES (8);
INSERT INTO schedule_blocks (block_id) VALUES (9);
INSERT INTO schedule_blocks (block_id) VALUES (10);
INSERT INTO schedule_blocks (block_id) VALUES (11);
INSERT INTO schedule_blocks (block_id) VALUES (12);
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (1, 1, '2:30-3:45 Friday', 'Taft');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (2, 2, '3:00-4:15 Wednesday', 'Taft');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (3, 3, '10:30-11:45 Wednesday', 'McKeeman');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (4, 4, '10:30-11:45 Friday', 'McKeeman');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (5, 5, '4:30-5:45 Monday', 'Rieker');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (6, 5, '4:30-5:45 Wednesday', 'Rieker');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (7, 6, '4:30-5:45 Tuesday', 'Rieker');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (8, 6, '4:30-5:45 Thursday', 'Rieker');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (9, 7, '4:30-5:45 Friday', 'Rieker');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (10, 8, '3:00-4:15 Monday', 'Rieker');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (11, 8, '3:00-4:15 Wednesday', 'Rieker');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (12, 9, '3:00-4:15 Tuesday', 'Rieker');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (13, 9, '3:00-4:15 Thursday', 'Rieker');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (14, 10, '3:00-4:15 Friday', 'Rieker');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (15, 11, '7:30-8:45 Monday', 'Rutman');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (16, 11, '7:30-8:45 Wednesday', 'Rutman');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (17, 12, '7:30-8:45 Tuesday', 'Rutman');
INSERT INTO schedule_times (time_id, block_id, time_descr, instructor) VALUES (18, 12, '7:30-8:45 Thursday', 'Rutman');
