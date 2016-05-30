-- Albums

INSERT INTO album (name, artist_id, genre_id, release_date, total_tracks) VALUES
( "A Blessing",
  (SELECT artist_id FROM artist WHERE name = "John Hollenbeck"),
  (SELECT genre_id FROM genre WHERE description = "Jazz"),
  2005-01-01,
  7
);

INSERT INTO album (name, artist_id, genre_id, release_date, total_tracks) VALUES
( "A Love Supreme",
  (SELECT artist_id FROM artist WHERE name = "John Coltrane"),
  (SELECT genre_id FROM genre WHERE description = "Jazz"),
  1964-01-01,
  3
);


-- Tracks

INSERT INTO track (name, album_id, artist_id, genre_id, composer_id, release_date, track_num) VALUES
( "A Blessing",
  (SELECT album_id FROM album WHERE
    artist_id = (SELECT artist_id FROM artist WHERE name = "John Hollenbeck") AND
    name = "A Blessing"),
  (SELECT artist_id FROM artist WHERE name = "John Hollenbeck"),
  (SELECT genre_id FROM genre WHERE description = "Jazz"),
  (SELECT composer_id FROM composer WHERE first_name = "John" and last_name = "Hollenbeck"),
  2005-01-01,
  1
);

INSERT INTO track (name, album_id, artist_id, genre_id, composer_id, release_date, track_num) VALUES
( "Folkmoot",
  (SELECT album_id FROM album WHERE
    artist_id = (SELECT artist_id FROM artist WHERE name = "John Hollenbeck") AND
    name = "A Blessing"),
  (SELECT artist_id FROM artist WHERE name = "John Hollenbeck"),
  (SELECT genre_id FROM genre WHERE description = "Jazz"),
  (SELECT composer_id FROM composer WHERE first_name = "John" and last_name = "Hollenbeck"),
  2005-01-01,
  2
);

INSERT INTO track (name, album_id, artist_id, genre_id, composer_id, release_date, track_num) VALUES
( "RAM",
  (SELECT album_id FROM album WHERE
    artist_id = (SELECT artist_id FROM artist WHERE name = "John Hollenbeck") AND
    name = "A Blessing"),
  (SELECT artist_id FROM artist WHERE name = "John Hollenbeck"),
  (SELECT genre_id FROM genre WHERE description = "Jazz"),
  (SELECT composer_id FROM composer WHERE first_name = "John" and last_name = "Hollenbeck"),
  2005-01-01,
  3
);

INSERT INTO track (name, album_id, artist_id, genre_id, composer_id, release_date, track_num) VALUES
( "Weiji",
  (SELECT album_id FROM album WHERE
    artist_id = (SELECT artist_id FROM artist WHERE name = "John Hollenbeck") AND
    name = "A Blessing"),
  (SELECT artist_id FROM artist WHERE name = "John Hollenbeck"),
  (SELECT genre_id FROM genre WHERE description = "Jazz"),
  (SELECT composer_id FROM composer WHERE first_name = "John" and last_name = "Hollenbeck"),
  2005-01-01,
  4
);

INSERT INTO track (name, album_id, artist_id, genre_id, composer_id, release_date, track_num) VALUES
( "Abstinence",
  (SELECT album_id FROM album WHERE
    artist_id = (SELECT artist_id FROM artist WHERE name = "John Hollenbeck") AND
    name = "A Blessing"),
  (SELECT artist_id FROM artist WHERE name = "John Hollenbeck"),
  (SELECT genre_id FROM genre WHERE description = "Jazz"),
  (SELECT composer_id FROM composer WHERE first_name = "John" and last_name = "Hollenbeck"),
  2005-01-01,
  5
);

INSERT INTO track (name, album_id, artist_id, genre_id, composer_id, release_date, track_num) VALUES
( "April",
  (SELECT album_id FROM album WHERE
    artist_id = (SELECT artist_id FROM artist WHERE name = "John Hollenbeck") AND
    name = "A Blessing"),
  (SELECT artist_id FROM artist WHERE name = "John Hollenbeck"),
  (SELECT genre_id FROM genre WHERE description = "Jazz"),
  (SELECT composer_id FROM composer WHERE first_name = "John" and last_name = "Hollenbeck"),
  2005-01-01,
  6
);

INSERT INTO track (name, album_id, artist_id, genre_id, composer_id, release_date, track_num) VALUES
( "The Music of Life",
  (SELECT album_id FROM album WHERE
    artist_id = (SELECT artist_id FROM artist WHERE name = "John Hollenbeck") AND
    name = "A Blessing"),
  (SELECT artist_id FROM artist WHERE name = "John Hollenbeck"),
  (SELECT genre_id FROM genre WHERE description = "Jazz"),
  (SELECT composer_id FROM composer WHERE first_name = "John" and last_name = "Hollenbeck"),
  2005-01-01,
  7
);


INSERT INTO track (name, album_id, artist_id, genre_id, composer_id, release_date, track_num) VALUES
( "Quartet: Acknowledgement",
  (SELECT album_id FROM album WHERE
    artist_id = (SELECT artist_id FROM artist WHERE name = "John Coltrane") AND
    name = "A Love Supreme"),
  (SELECT artist_id FROM artist WHERE name = "John Coltrane"),
  (SELECT genre_id FROM genre WHERE description = "Jazz"),
  (SELECT composer_id FROM composer WHERE first_name = "John" and last_name = "Coltrane"),
  1964-01-01,
  1
);

INSERT INTO track (name, album_id, artist_id, genre_id, composer_id, release_date, track_num) VALUES
( "Resolution",
  (SELECT album_id FROM album WHERE
    artist_id = (SELECT artist_id FROM artist WHERE name = "John Coltrane") AND
    name = "A Love Supreme"),
  (SELECT artist_id FROM artist WHERE name = "John Coltrane"),
  (SELECT genre_id FROM genre WHERE description = "Jazz"),
  (SELECT composer_id FROM composer WHERE first_name = "John" and last_name = "Coltrane"),
  1964-01-01,
  2
);

INSERT INTO track (name, album_id, artist_id, genre_id, composer_id, release_date, track_num) VALUES
( "Purusance",
  (SELECT album_id FROM album WHERE
    artist_id = (SELECT artist_id FROM artist WHERE name = "John Coltrane") AND
    name = "A Love Supreme"),
  (SELECT artist_id FROM artist WHERE name = "John Coltrane"),
  (SELECT genre_id FROM genre WHERE description = "Jazz"),
  (SELECT composer_id FROM composer WHERE first_name = "John" and last_name = "Coltrane"),
  1964-01-01,
  3
);

April In Paris	Count Basie & His Orchestra	April In Paris	Jazz	1	Vernon Duke (1903-1969) b. Vladimir Dukelsky & Edgar Yipsel "Yip" Harburg (1896-1981); arr. William Strethen "Wild Bill" Davis (1918-1995)	1997	1 of 17
Corner Pocket	Count Basie & His Orchestra	April In Paris	Jazz	1	Freddie Green (1911-1987)	1997	2 of 17
Didn't You?	Count Basie & His Orchestra	April In Paris	Jazz	1	Frank Benjamin Foster III (b.1928)	1997	3 of 17
Sweetie Cakes	Count Basie & His Orchestra	April In Paris	Jazz	1	Ernest Brooks "Ernie" Wilkins (1922-1999)	1997	4 of 17
Magic	Count Basie & His Orchestra	April In Paris	Jazz	1	Frank Wellington Wess (b. 1922)	1997	5 of 17
Shiny Stockings	Count Basie & His Orchestra	April In Paris	Jazz	1	Frank Benjamin Foster III (b.1928)	1997	6 of 17
What Am I Here For?	Count Basie & His Orchestra	April In Paris	Jazz	1	Edward Kennedy "Duke" Ellington (1899-1974), arr. Frank Benjamin Foster III (b. 1928)	1997	7 of 17
Midgets	Count Basie & His Orchestra	April In Paris	Jazz	1	Joseph Dwight "Joe" Newman (1922-1992)	1997	8 of 17
Mambo Inn	Count Basie & His Orchestra	April In Paris	Jazz	1	Mario BauzÃ¡ (1911-1993), Edgar Melvin Sampson (1907-1973), & Bobby Woodlen (), arr. Frank Bejamin Foster III (b. 1928)	1997	9 of 17
Dinner With Friends	Count Basie & His Orchestra	April In Paris	Jazz	1	Neal Hefti (b. 1922)	1997	10 of 17
April In Paris	Count Basie & His Orchestra	April In Paris	Jazz	1	Vernon Duke (1903-1969) b. Vladimir Dukelsky & Edgar Yipsel "Yip" Harburg (1896-1981); arr. William Strethen "Wild Bill" Davis (1918-1995)	1997	11 of 17
Corner Pocket	Count Basie & His Orchestra	April In Paris	Jazz	1	Freddie Green (1911-1987)	1997	12 of 17
Didn't You?	Count Basie & His Orchestra	April In Paris	Jazz	1	Frank Benjamin Foster III (b.1928)	1997	13 of 17
Magic	Count Basie & His Orchestra	April In Paris	Jazz	1	Frank Wellington Wess (b. 1922)	1997	14 of 17
Magic	Count Basie & His Orchestra	April In Paris	Jazz	1	Frank Wellington Wess (b. 1922)	1997	15 of 17
What Am I Here For?	Count Basie & His Orchestra	April In Paris	Jazz	1	Edward Kennedy "Duke" Ellington (1899-1974), arr. Frank Benjamin Foster III (b. 1928)	1997	16 of 17
Midgets	Count Basie & His Orchestra	April In Paris	Jazz	1	Joseph Dwight "Joe" Newman (1922-1992)	1997	17 of 17

I Don't Stand A Ghost Of A Chance With You	Clifford Brown	Brown And Roach	Jazz		Victor Young/Bing Crosby/Ned Washington	1954	2 of 7
I Get A Kick Out Of You	Clifford Brown	Brown And Roach	Jazz		Cole Porter	1954	7 of 7
Sweet Clifford	Clifford Brown & Max Roach	Brown And Roach	Jazz		Clifford Brown	1954	1 of 7
Stompin' At The Savoy	Clifford Brown & Max Roach	Brown And Roach	Jazz		Edgar Sampson/Benny Goodman/Chick Webb/Chick William Webb/Andy Razaf	1954	3 of 7
I'll String Along With You	Clifford Brown & Max Roach	Brown And Roach	Jazz		Harry Warren/Al Dubin	1954	4 of 7
Mildama	Clifford Brown & Max Roach	Brown And Roach	Jazz		Max Roach	1954	5 of 7
Darn That Dream	Clifford Brown & Max Roach	Brown And Roach	Jazz		Eddie DeLange/Jimmy Heusen	1954	6 of 7

Harlequin Tears	Toshiko Akiyoshi Big Band	Desert Lady / Fantasy	Jazz		T. Akiyoshi	1993	1 of 6
Desert Lady-Fantasy	Toshiko Akiyoshi Big Band	Desert Lady / Fantasy	Jazz		T. Akiyoshi, L. Tabackin	1993	2 of 6
Hangin' Loose	Toshiko Akiyoshi Big Band	Desert Lady / Fantasy	Jazz		T. Akiyoshi	1993	3 of 6
Hiroko's Delight	Toshiko Akiyoshi Big Band	Desert Lady / Fantasy	Jazz		T. Akiyoshi	1993	4 of 6
Broken Dreams	Toshiko Akiyoshi Big Band	Desert Lady / Fantasy	Jazz		L. Tabackin	1993	5 of 6
Bebop	Toshiko Akiyoshi Big Band	Desert Lady / Fantasy	Jazz		D.Gilespie	1993	6 of 6

Forward March	Pat Metheny Group	First Circle	Jazz		Pat Metheny/Pat Metheny & Lyle Mays	1984	1 of 8
Yolanda, You Learn	Pat Metheny Group	First Circle	Jazz		Lyle Mays	1984	2 of 8
The First Circle	Pat Metheny Group	First Circle	Jazz		Lyle Mays	1984	3 of 8
If I Could	Pat Metheny Group	First Circle	Jazz		Pat Metheny/Pat Metheny & Lyle Mays	1984	4 of 8
Tell It All	Pat Metheny Group	First Circle	Jazz		Lyle Mays	1984	5 of 8
End Of The Game	Pat Metheny Group	First Circle	Jazz		Lyle Mays	1984	6 of 8
MÃ¡s AllÃ¡ (Beyond)	Pat Metheny Group	First Circle	Jazz		Pat Metheny/Pat Metheny & Lyle Mays	1984	7 of 8
Praise	Pat Metheny Group	First Circle	Jazz		Lyle Mays	1984	8 of 8

To Defy The Laws Of Tradition	Primus	Frizzle Fry	Alternative		Not Documented	1990	1 of 14
Groundhog's Day	Primus	Frizzle Fry	Alternative		Not Documented	1990	2 of 14
Too Many Puppies	Primus	Frizzle Fry	Alternative		Not Documented	1990	3 of 14
Mr. Knowitall	Primus	Frizzle Fry	Alternative		Not Documented	1990	4 of 14
Frizzle Fry	Primus	Frizzle Fry	Alternative		Not Documented	1990	5 of 14
John The Fisherman	Primus	Frizzle Fry	Alternative		Not Documented	1990	6 of 14
You Can't Kill Michael Malloy	Primus	Frizzle Fry	Alternative		Not Documented	1990	7 of 14
The Toys Go Winding Down	Primus	Frizzle Fry	Alternative		Not Documented	1990	8 of 14
Pudding Time	Primus	Frizzle Fry	Alternative		Not Documented	1990	9 of 14
Sathington Willoughby	Primus	Frizzle Fry	Alternative		Not Documented	1990	10 of 14
Spegetti Western	Primus	Frizzle Fry	Alternative		Not Documented	1990	11 of 14
Harold Of The Rocks	Primus	Frizzle Fry	Alternative		Not Documented	1990	12 of 14
To Defy	Primus	Frizzle Fry	Alternative		Not Documented	1990	13 of 14
Hello Skinny / Constantinople	Primus	Frizzle Fry	Alternative		Not Documented	1990	14 of 14

Heart Of Darkness	Conrad Herwig	Heart Of Darkness	Jazz			2009	1 of 8
Secret Sharer	Conrad Herwig	Heart Of Darkness	Jazz			2009	2 of 8
Inner Sincerity	Conrad Herwig	Heart Of Darkness	Jazz			2009	3 of 8
Silent Tears	Conrad Herwig	Heart Of Darkness	Jazz			2009	4 of 8
The Instigator	Conrad Herwig	Heart Of Darkness	Jazz			2009	5 of 8
Watch Your Steps	Conrad Herwig	Heart Of Darkness	Jazz			2009	6 of 8
The Lamp Is Low	Conrad Herwig	Heart Of Darkness	Jazz			2009	7 of 8
Tilt	Conrad Herwig	Heart Of Darkness	Jazz			2009	8 of 8

Jackie-Ing	Thelonious Monk	In Italy [Live]	Jazz		Thelonious Monk	1963	1 of 8
Epistrophy	Thelonious Monk	In Italy [Live]	Jazz		Kenny Clarke/Thelonious Monk	1963	2 of 8
Body And Soul	Thelonious Monk	In Italy [Live]	Jazz		Frank Eyton/John Green/Edward Heyman/Robert Sour	1963	3 of 8
Straight, No Chaser	Thelonious Monk	In Italy [Live]	Jazz		Thelonious Monk	1963	4 of 8
Bemsha Swing	Thelonious Monk	In Italy [Live]	Jazz		Thelonious Monk	1963	5 of 8
San Francisco Holiday	Thelonious Monk	In Italy [Live]	Jazz		Thelonious Monk	1963	6 of 8
Crepuscule With Nellie	Thelonious Monk	In Italy [Live]	Jazz		Thelonious Monk	1963	7 of 8
Rhythm-A-Ning	Thelonious Monk	In Italy [Live]	Jazz		Thelonious Monk	1963	8 of 8

Inner Urge	Joe Henderson	Inner Urge	Jazz		Joe Henderson	1964	1 of 5
Isotope	Joe Henderson	Inner Urge	Jazz		Joe Henderson	1964	2 of 5
El Barrio	Joe Henderson	Inner Urge	Jazz		Manny Albem	1964	3 of 5
You Know I Care	Joe Henderson	Inner Urge	Jazz		Duke Pearson	1964	4 of 5
Night And Day	Joe Henderson	Inner Urge	Jazz		Cole Porter	1964	5 of 5

Handel: Messiah - Hallelujah Chorus	Arleen AugÃ©r, Anne Sofie Von Otter, Etc.; Trevor Pinnock: The English Concert & Choir	Kerman LISTEN [Disc 2]	Classical	2	George Frideric Handel	1992	7 of 17
Mozart: Symphony #40 In G Minor, K 550 - 1. Molto Allegro	Colin Davis: Staatskapelle Dresden	Kerman LISTEN [Disc 2]	Classical	2	Wolfgang Amadeus Mozart	1992	16 of 17
String Quartet in D (The Lark), Op. 64 No. 5, III	Franz Joseph Haydn	Kerman LISTEN [Disc 2]	Classical	2	Haydn	1992	12 of 17
Symphony No. 88 in G, IV	Franz Joseph Haydn	Kerman LISTEN [Disc 2]	Classical	2	Haydn	1992	15 of 17
String Quartet in D (The Lark), Op. 64 No. 5, IV	Franz Joseph Haydn	Kerman LISTEN [Disc 2]	Classical	2	Haydn	1992	17 of 17
Rodelinda: "Massime CosÃ¬ indegne," "Tirannia"	George Frideric Handel	Kerman LISTEN [Disc 2]	Classical	2	Handel	1992	5 of 17
Messiah: "There were sheperds," "Glory to God"	George Frideric Handel	Kerman LISTEN [Disc 2]	Classical	2	Handel	1992	6 of 17
Brandenburg Concerto No. 5, I / Orchestral Suite No. 3 in D	Johann Sebastian Bach	Kerman LISTEN [Disc 2]	Classical	2	Johann Sebastian Bach	1992	1 of 17
Air	Johann Sebastian Bach	Kerman LISTEN [Disc 2]	Classical	2	Johann Sebastian Bach	1992	2 of 17
Gavotte	Johann Sebastian Bach	Kerman LISTEN [Disc 2]	Classical	2	Johann Sebastian Bach	1992	3 of 17
BourrÃ©e	Johann Sebastian Bach	Kerman LISTEN [Disc 2]	Classical	2	Johann Sebastian Bach	1992	4 of 17
Christmas Oratorio: "Wie soll ich dich empfangen"	Johann Sebastian Bach	Kerman LISTEN [Disc 2]	Classical	2	Johann Sebastian Bach	1992	8 of 17
Christmas Oratorio: "Nun seid ihr wohl gerochen"	Johann Sebastian Bach	Kerman LISTEN [Disc 2]	Classical	2	Johann Sebastian Bach	1992	9 of 17
Chorale Prelude: "Herzlich tut mich verlangen"	Johann Sebastian Bach	Kerman LISTEN [Disc 2]	Classical	2	Johann Sebastian Bach	1992	10 of 17
Mozart: Piano Concerto #17 In G, K 453 - 3. Allegretto	Malcolm Bilson; John Eliot Gardiner: English Baroque Soloists	Kerman LISTEN [Disc 2]	Classical	2	Wolfgang Amadeus Mozart	1992	14 of 17
Mozart: Don Giovanni, K 527 - Overture	Neville Marriner: Academy Of St. Martin In The Fields	Kerman LISTEN [Disc 2]	Classical	2	Wolfgang Amadeus Mozart	1992	13 of 17
Menuetto	Salieri	Kerman LISTEN [Disc 2]	Classical	2	Salieri	1992	11 of 17

Cantaloupe Island	Poncho Sanchez	Psychedelic Blues	Jazz		Herbie Hancock	2009	1 of 10
Crisis	Poncho Sanchez	Psychedelic Blues	Jazz		Freddie Hubbard	2009	2 of 10
Psychedelic Blues	Poncho Sanchez	Psychedelic Blues	Jazz		Sonny Henry	2009	3 of 10
Willie Bobo Medley	Poncho Sanchez	Psychedelic Blues	Jazz		Sonny Henry/Willie Bobo	2009	4 of 10
Grand Central	Poncho Sanchez	Psychedelic Blues	Jazz		John Coltrane	2009	5 of 10
Slowly But Surely	Poncho Sanchez	Psychedelic Blues	Jazz		John Hicks	2009	6 of 10
Silver's Seranade	Poncho Sanchez	Psychedelic Blues	Jazz		Horace Silver	2009	7 of 10
The One Ways	Poncho Sanchez	Psychedelic Blues	Jazz		David Torres	2009	8 of 10
Delifonse	Poncho Sanchez	Psychedelic Blues	Jazz		Poncho Sanchez/Francisco Torres	2009	9 of 10
Con Sabor Latino	Poncho Sanchez	Psychedelic Blues	Jazz		Rene Touzet	2009	10 of 10

Mirage	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		Pete Rugolo	1950	1 of 16
Conflict	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		Pete Rugolo	1950	2 of 16
Solitaire	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		Bill Russo	1950	3 of 16
Soliloquy	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		Johnny Richards	1950	4 of 16
Theme for Sunday	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		Stan Kenton	1950	5 of 16
Amazonia	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		Laurindo Almeida	1950	6 of 16
Lonesome Road	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		N. Shilkret-G. Austin	1950	7 of 16
Trajectories	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		Franklyn Marks	1950	8 of 16
Incident in Jazz	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		Bob Graettinger	1950	9 of 16
Cuban Episode	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		Chico O'Farrill	1950	10 of 16
Evening in Pakistan	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		Franklyn Marks	1950	11 of 16
Salute	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		Pete Rugolo	1950	12 of 16
Mardi Gras	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		L. Almeida-M. Sunshine	1950	13 of 16
In Veradero	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		Neal Hefti	1950	14 of 16
Jolly Rogers	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		Shorty Rogers	1950	15 of 16
Blues in Riff	Stan Kenton & His Orchestra	Stan Kenton: The Innovations Orchestra [Disc 1]	Jazz		Pete Rugolo	1950	16 of 16

Azucar Negra	Celia Cruz	The 20th Anniversary of the New York Salsa Festival 1975-1995	Latin		Various Artists	1995	4 of 11
Los Tenis	El Gran Combo	The 20th Anniversary of the New York Salsa Festival 1975-1995	Latin		Various Artists	1995	11 of 11
Por Eso Yo Canto Salsa	Fania All-Stars	The 20th Anniversary of the New York Salsa Festival 1975-1995	Latin		Various Artists	1995	2 of 11
El Cantante	HÃ©ctor Lavoe	The 20th Anniversary of the New York Salsa Festival 1975-1995	Latin		Various Artists	1995	5 of 11
Mi Primera Rumba	La India, Eddie Palmieri	The 20th Anniversary of the New York Salsa Festival 1975-1995	Latin		Various Artists	1995	8 of 11
Salsa Caliente	Orquesta De La Luz	The 20th Anniversary of the New York Salsa Festival 1975-1995	Latin		Various Artists	1995	10 of 11
Mentiras	Oscar D'LeÃ³n	The 20th Anniversary of the New York Salsa Festival 1975-1995	Latin		Various Artists	1995	9 of 11
Introduccion	Paco Navarro	The 20th Anniversary of the New York Salsa Festival 1975-1995	Latin		Various Artists	1995	1 of 11
Sonora Pa'l Bailador	Sonora PonceÃ±a	The 20th Anniversary of the New York Salsa Festival 1975-1995	Latin		Various Artists	1995	6 of 11
Dejame SoÃ±ar	Tito Puente & Tony Vega	The 20th Anniversary of the New York Salsa Festival 1975-1995	Latin		Various Artists	1995	7 of 11
Pedro Navaja	Willie ColÃ³n & RubÃ©n Blades	The 20th Anniversary of the New York Salsa Festival 1975-1995	Latin		Various Artists	1995	3 of 11
