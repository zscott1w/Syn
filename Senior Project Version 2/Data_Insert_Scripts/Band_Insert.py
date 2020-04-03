#Insert into Band, Location, Genre, and BandGenres tables
#!/usr/bin/env python3

import sys, csv, MySQLdb

def main():
    #Data removed for sercurity
	mydb = MySQLdb.connect(
		host="",
		user="",
		passwd="",
		database=""
	)

	cursor_genre = mydb.cursor()
	cursor_loc = mydb.cursor()
	cursor_band = mydb.cursor()

	with open('./band.csv') as f:
		readCSV = csv.DictReader(f, delimiter=',')
		#genre_query
		genre_query = "Insert into Genre(genre_name) values ('{0}')"
		genre_query_select = "Select genre_id from Genre where genre_name = '{0}'"
		#location_query
		location_query = "Insert into Location(city, state, country) values ('{0}','{1}','{2}')"
		location_query_select = "Select location_id from Location where city = '{0}'"
		#band_query
        band_query_select = "Select band_id from Band where band_id = {0}"
		band_query = "Insert into Band(band_id, band_name, location_id, member_count, poc_flag, female_flag, lgbtq_flag) values ({0},'{1}',{2},{3},'{4}','{5}','{6}')"
		#band_genre_insert
		band_genre_query = "Insert into BandGenres(band_id, genre_id) values({0},{1})"
		for row in readCSV:

            #If row does not have a band (empty) skip it
            if(row['Band Name'].rstrip() == ''):
                continue

            #Genre Search and Collect
		    cursor_genre.execute(genre_query_select.format(row['Genre'].rstrip().replace(' ', '')))
		    g = cursor_genre.fetchone()
            if(g is None):
                try:
		            cursor_genre.execute(genre_query.format(row['Genre'].rstrip().replace(' ', '')))
                    genre_id = cursor_genre.lastrowid
                    mydb.commit()
                except Exception as E:
                    print(E)
            else:
                genre_id = g[0]

            #Location Search and Collections
		    cursor_loc.execute(location_query_select.format(row['Hometown'].rstrip().strip()))
		    l = cursor_loc.fetchone()

            #There is a case where cities can have the same name, but it does not happen in my data
		    if(l is None):
                try:
                    cursor_loc.execute(location_query.format(row['Hometown'].rstrip().strip(),row['State'].rstrip().replace(' ','')
                    ,row['Country'].rstrip().strip()))
                    loc_id = cursor_loc.lastrowid
                    mydb.commit()
                except Exception as E:
                    print(E)
            else:
                loc_id = l[0]

            #Band Search and Insert
		    cursor_band.execute(band_query_select.format(row['ID'].rstrip().strip()))
		    b = cursor_band.fetchone()

            #There is a case where bands can have the same name, but it does not happen in my data
		    if(b is None):
                try:
                    cursor_band.execute(band_query.format(row['ID'].rstrip(), row['Band Name'].rstrip().strip(),loc_id,row['# of Members'].rstrip().replace(' ','')
                    ,row['POC Members?'].rstrip().replace(' ',''),row['Female Members?'].rstrip().replace(' ',''),row['LGBTQ? (if apparent)'].rstrip().replace(' ','')))
                    band_id = cursor_band.lastrowid
                    mydb.commit()
                except Exception as E:
                    print(E)
            else:
                band_id = b[0]

			#BandGenre Table Insert
            if(b is None):
                try:
                    cursor_genre.execute(band_genre_query.format(band_id, genre_id))
                    mydb.commit()
                except Exception as E:
                    print(E)

        mydb.close()

main()
