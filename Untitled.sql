SELECT track.track_id, track.name, track.genre_id,
                           track.release_date, track.track_num,
                           artist.name, composer.first_name, composer.last_name
                    FROM track
                    INNER JOIN track_artist ON track_artist.track_id = track.track_id
                    INNER JOIN artist ON track_artist.artist_id = artist.artist_id
                    INNER JOIN track_composer ON track_composer.track_id = track.track_id
                    INNER JOIN composer ON track_composer.composer_id = composer.composer_id
                    WHERE album_id = 19;
                    
SELECT * FROM album; 
SELECT * FROM track;
DELETE FROM track;
SELECT * FROM ainstance; 
SELECT * FROM track_composer;
DELETE FROM track_composer;
SHOW artist;
SELECT count(*) AS total FROM artist;

SELECT * FROM composer ORDER BY last_name;
SELECT * FROM ustatus;
SELECT * FROM utype;
SELECT * FROM user;
SELECT * FROM ustatus;
DELETE FROM ustatus;
INSERT INTO ustatus (ustatus_id, description) VALUES (3,'FINE BLOCK');
SELECT * FROM ustatus;
DELETE FROM ustatus WHERE ustatus_id IS NULL;

SELECT user.user_id, user.utype_id, utype.description,
                     user.ustatus_id, user.first_name,
                     user.last_name, user.email
              FROM user 
              INNER JOIN utype ON user.utype_id = utype.utype_id 
              ORDER BY 'last_name' ASC ;              
SELECT * FROM user;