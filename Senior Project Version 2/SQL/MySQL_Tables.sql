CREATE TABLE Genre(genre_id int AUTO_INCREMENT, genre_name varchar(50) NOT NULL, PRIMARY KEY(genre_id));

CREATE TABLE Weather(weather_id int AUTO_INCREMENT, weather_type varchar(50) NOT NULL, PRIMARY KEY(weather_id));

CREATE TABLE Location(location_id int AUTO_INCREMENT, street_address varchar(50), city varchar(50) NOT NULL, state varchar(2), zip int,
country varchar(50) NOT NULL, PRIMARY KEY(location_id));

CREATE TABLE Band(band_id int AUTO_INCREMENT, band_name varchar(50) NOT NULL, location_id int,
member_count int, poc_flag char(1), female_flag char(1), lgbtq_flag char(1),
PRIMARY KEY(band_id), FOREIGN KEY(location_id) REFERENCES Location(location_id), UNIQUE(band_name));

CREATE TABLE Venue(venue_id int AUTO_INCREMENT, venue_name varchar(50) NOT NULL, location_id int NOT NULL,
PRIMARY KEY(venue_id), FOREIGN KEY(location_id) REFERENCES Location(location_id));

CREATE TABLE Shows(show_id int AUTO_INCREMENT, venue_id int NOT NULL, show_date date NOT NULL, show_time time,
number_of_acts int, weather_id int, ticket_price int, ig_flag char(1), fb_flag char(1),
physical_ad_flag char(1), paid_ad_flag char(1), success_flag char(1), profit_flag char(1),
charity_show_flag char(1), PRIMARY KEY(show_id), FOREIGN KEY(venue_id) REFERENCES Venue(venue_id),
FOREIGN KEY(weather_id) REFERENCES Weather(weather_id));

CREATE TABLE BandGenres(band_id int AUTO_INCREMENT, genre_id int, FOREIGN KEY(band_id) REFERENCES Band(band_id),
FOREIGN KEY(genre_id) REFERENCES Genre(genre_id), PRIMARY KEY(band_id, genre_id));

CREATE TABLE ShowsBands(show_id int AUTO_INCREMENT, band_id int, FOREIGN KEY(show_id) REFERENCES Shows(show_id),
FOREIGN KEY(band_id) REFERENCES Band(band_id), PRIMARY KEY(show_id, band_id));

CREATE TABLE UserID(user_id varchar(1000) NOT NULL, user_email varchar(1000) NOT NULL, user_password varchar(1000) NOT NULL,
user_type varchar(45), creation_date datetime, last_login datetime, activation_code varchar(32), active int, affiliation varchar(60),
KEY(user_id), UNIQUE(affiliation)); 
