SELECT * FROM track;

SELECT * FROM track WHERE AlbumId = "?" LIMIT 25;

SELECT * FROM track WHERE Composer = "?" LIMIT 25;

SELECT * FROM track WHERE  GenreId = "?" LIMIT 25;

SELECT * FROM track WHERE MediaType = "?" LIMIT 25;


--Get all tracks made by an artist
SELECT artist.Name, album.Title, track.Name FROM track LEFT JOIN album ON track.AlbumId = album.AlbumId LEFT JOIN artist ON album.ArtistId = artist.ArtistId WHERE artist.Name = "?";