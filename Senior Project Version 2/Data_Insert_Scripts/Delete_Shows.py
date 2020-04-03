#Delete data from the Show, ShowsBands, and Venue tables
#!/usr/bin/env python3

import sys, csv, MySQLdb

def main():
	#Cleared for Security
	mydb = MySQLdb.connect(
		host="",
		user="",
		passwd="",
		database=""
	)

	#Cursor to delete all data from show tables
	cursor = mydb.cursor()

    try:
        cursor.execute("Delete from ShowsBands")
        mydb.commit()
        cursor.execute("Delete from Shows")
        mydb.commit()
		cursor.execute("Delete from Venue")
        mydb.commit()
    except Exception as E:
        print(E)

    mydb.close()

main()
