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
	bynr SMALLINT(1) NOT NULL,   /* offered by NR */
	byfellow SMALLINT(1) NOT NULL,   /* offered by an Engineering Fellow */
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
	needsnr SMALLINT(1) NOT NULL DEFAULT 0,
                            /* must be in section with NR, not Fellow */
	PRIMARY KEY(login)
);

/* Prepopulation */


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
INSERT INTO schedule_blocks (block_id) VALUES (13);
INSERT INTO schedule_blocks (block_id) VALUES (14);
INSERT INTO schedule_blocks (block_id) VALUES (15);
INSERT INTO schedule_blocks (block_id) VALUES (16);
INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (1, 1, '10:30-11:45 Monday', 'McKeeman', 0, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (2, 1, '10:30-11:45 Wednesday', 'Brodzik', 0, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (3, 2, '10:30-11:45 Tuesday', 'Brodzik', 0, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (4, 2, '10:30-11:45 Thursday', 'Brodzik', 0, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (5, 3, '10:30-11:45 Friday', 'Brodzik or McKeeman', 0, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (6, 4, '12:00-1:15 Tuesday', 'Ramsey', 1, 0);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (7, 4, '12:00-1:15 Thursday', 'Ramsey', 1, 0);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (8, 5, '1:30-2:45 Tuesday', 'Ramsey', 1, 0);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (9, 5, '1:30-2:45 Thursday', 'Ramsey', 1, 0);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (10, 6, '2:30-3:45 Friday', 'Taft', 0, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (11, 7, '3:00-4:15 Monday', 'Ramsey or Rieker', 1, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (12, 7, '3:00-4:15 Wednesday', 'Ramsey or Rieker', 1, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (13, 8, '3:00-4:15 Tuesday', 'Rieker', 0, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (14, 8, '3:00-4:15 Thursday', 'Ramsey or Rieker', 1, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (15, 9, '3:00-4:15 Friday', 'Ramsey or Rieker or Taft', 1, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (16, 10, '4:30-5:45 Monday', 'Rieker or Stoy', 0, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (17, 10, '4:30-5:45 Wednesday', 'Ramsey or Rieker', 1, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (18, 11, '4:30-5:45 Tuesday', 'Rieker or Stoy', 0, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (19, 11, '4:30-5:45 Thursday', 'Rieker or Stoy', 0, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (20, 12, '4:30-5:45 Friday', 'Ramsey or Rieker', 1, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (21, 13, '6:00-7:15 Wednesday', 'Ramsey', 1, 0);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (22, 14, '6:00-7:15 Thursday', 'Ramsey', 1, 0);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (23, 15, '7:30-8:45 Wednesday', 'Brodzik', 0, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (24, 16, '7:30-8:45 Tuesday', 'Brodzik', 0, 1);

INSERT INTO schedule_times
              (time_id, block_id, time_descr, instructor, bynr, byfellow)
  VALUES (25, 16, '7:30-8:45 Thursday', 'Brodzik', 0, 1);

