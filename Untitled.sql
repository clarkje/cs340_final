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