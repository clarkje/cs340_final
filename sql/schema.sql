-- Jeromie Clark, Andrew Kroes
-- CS340 Final Project
-- Database Schema

-- iTunes doesn't handle separate first and last names, esp. in sorting
-- For instance, Angelo Badalamenti precedes Anonymous
-- We'll just treat it as a single field for simplicity, but I'm open to negotiation
-- TODO: Where our source data includes lists in the column, we'll need to put those in individual artist records

CREATE TABLE artist (
  artist_id INT(16) PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL
);

CREATE TABLE genre (
  genre_id INT(4) PRIMARY KEY AUTO_INCREMENT,
  description varchar(255)
);

CREATE TABLE composer (
  composer_id INT(16) PRIMARY KEY AUTO_INCREMENT,
  first_name varchar(128),
  last_name varchar(128),
  CHECK (first_name IS NOT NULL OR last_name IS NOT NULL)
);

-- An album has a headlining artist and genre
-- Individual tracks may have distinct artist and genre values that differ
-- total_tracks = track 12 of N
-- The combination of artist and album name should be distinct
CREATE TABLE album (
  album_id INT(16) PRIMARY KEY AUTO_INCREMENT,
  artist_id INT(16) NOT NULL,
  genre_id INT(4) NOT NULL,
  name VARCHAR(255),
  release_date DATE,
  total_tracks INT(2),
  CONSTRAINT album_unqiue_name_artist UNIQUE (name, artist_id),
  FOREIGN KEY (artist_id) REFERENCES artist (artist_id),
  FOREIGN KEY (genre_id) REFERENCES genre (genre_id)
);

CREATE TABLE astatus (
  astatus_id INT(16) PRIMARY KEY AUTO_INCREMENT,
  description VARCHAR(64)
);

-- The library may have multiple copies of an item
-- ainstance (album instance) tracks the status of each individual copy of an album
CREATE TABLE ainstance (
  ainstance_id INT(16) PRIMARY KEY AUTO_INCREMENT,
  album_id INT(16) NOT NULL,
  astatus_id INT(2) NOT NULL,
  location VARCHAR(255),
  FOREIGN KEY (astatus_id) REFERENCES astatus (astatus_id),
  FOREIGN KEY (album_id) REFERENCES album (album_id)

);

-- Tracks can have a single genre and multiple artists and composers
-- Compilations can include tracks from multiple years
  CREATE TABLE track (
    track_id INT(32) PRIMARY KEY AUTO_INCREMENT,
    album_id INT(16),
    genre_id INT(4),
    name VARCHAR(255),
    release_date DATE,
    track_num INT(2),
    CONSTRAINT unique_track_name_album UNIQUE (name, album_id),
    FOREIGN KEY (genre_id) REFERENCES genre (genre_id),
    FOREIGN KEY (album_id) REFERENCES album (album_id)
  );

CREATE TABLE track_artist (
  track_id INT(32) NOT NULL,
  artist_id INT(16) NOT NULL,
  FOREIGN KEY (track_id) REFERENCES track (track_id),
  FOREIGN KEY (artist_id) REFERENCES artist (artist_id)
);

-- TODO: BUG - You shouldn't be able to insert the same composer/track pair multiple times --
-- Code Fixed, AK
CREATE TABLE track_composer (
  track_id INT(32) NOT NULL,
  composer_id INT(16) NOT NULL,
  FOREIGN KEY (track_id) REFERENCES track (track_id),
  FOREIGN KEY (composer_id) REFERENCES composer (composer_id),
  CONSTRAINT unique_track_composer UNIQUE (track_id, composer_id)
);

-- utype is the type of user: Admin / Patron (Undergrad v. Graduate?) / Clerk / etc.
CREATE TABLE utype (
  utype_id INT(2) PRIMARY KEY AUTO_INCREMENT,
  description VARCHAR(255)
);

-- Possible status: Active / Inactive / Blocked / Fine - Fine Amt.
CREATE TABLE ustatus (
  ustatus_id INT(2) PRIMARY KEY AUTO_INCREMENT,
  description VARCHAR(255)
);

-- Library Users
-- utype is the type of user: Admin / Patron (Undergrad v. Graduate?) / Clerk / etc.
CREATE TABLE user (
  user_id INT(16) PRIMARY KEY AUTO_INCREMENT,
  utype_id INT(2) NOT NULL,
  ustatus_id INT(2) NOT NULL,
  first_name VARCHAR(128) NOT NULL,
  last_name VARCHAR(128) NOT NULL,
  email VARCHAR(255) NOT NULL,
  fine DECIMAL(5,2),
  FOREIGN KEY (utype_id) REFERENCES utype (utype_id),
  FOREIGN KEY (ustatus_id) REFERENCES ustatus (ustatus_id)
);

-- TODO: Can we ensure through MySQL constraints that the same album isn't checked
-- out multiple times, while still retaining history?

-- An album's lending history
CREATE TABLE ainstance_user (
  user_id INT(16),
  ainstance_id INT(16),
  checked_out DATE,
  due_by DATE,
  returned DATE,
  FOREIGN KEY (user_id) REFERENCES user (user_id),
  FOREIGN KEY (ainstance_id) REFERENCES ainstance (ainstance_id)
);
