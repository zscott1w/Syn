#Delete data from the Band, BandGenre, Location, and Genre tables
#!/usr/bin/env python3

import sys, csv, MySQLdb

def main():
    #Cleared for sercurity
	mydb = MySQLdb.connect(
		host="",
		user="",
		passwd="",
		database=""
	)

	#Delete from the Band and Location tables
	cursor_genre = mydb.cursor()
	cursor_loc = mydb.cursor()
	cursor_band = mydb.cursor()

    try:
        cursor_genre.execute("Delete from BandGenres")
        mydb.commit()
        cursor_band.execute("Delete from Band")
        mydb.commit()
		cursor_genre.execute("Delete from Genre")
        mydb.commit()
        cursor_loc.execute("Delete from Location")
        mydb.commit()
    except Exception as E:
        print(E)

    mydb.close()

main()
