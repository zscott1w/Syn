#Insert into the Show, Venue, Weather, and ShowsBands table from the csvfile
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

	cursor_shows = mydb.cursor()

	with open('./shows.csv') as f:
		readCSV = csv.DictReader(f, delimiter=',')
		#weather_query
		weather_query = "Insert into Weather(weather_type) values ('{0}')"
		weather_query_select = "Select weather_id from Weather where weather_type = '{0}'"
		#venue_query
		venue_query = "Insert into Venue(venue_name, location_id) values ('{0}',{1})"
		venue_query_select = "Select venue_id from Venue where venue_name = '{0}'"
		#show_query
        shows_query_select = "Select show_id from Shows where venue_id = {0} and show_date = '{1}'"
		shows_query = """Insert into Shows(venue_id, show_date, number_of_acts, weather_id, ticket_price, ig_flag,
        fb_flag, physical_ad_flag, paid_ad_flag, success_flag, profit_flag)
		values ({0},'{1}',{2},{3},{4},'{5}','{6}','{7}','{8}','{9}','{10}')"""
		#show_band_insert
        show_band_select = "Select * from ShowsBands where show_id = {0} and band_id = {1}"
		show_band_query = "Insert into ShowsBands(show_id, band_id) values({0},{1})"

        for row in readCSV:

			#If row does not have a venue (its empty) skip it
            if(row['Venue'].rstrip() == ''):
                continue

            #Weather Search and Collect
		    cursor_shows.execute(weather_query_select.format(row['Weather'].rstrip().replace(' ', '')))
		    w = cursor_shows.fetchone()
			if(w is None):
                try:
					cursor_shows.execute(weather_query.format(row['Weather'].rstrip().replace(' ', '')))
					weather_id = cursor_shows.lastrowid
					mydb.commit()
				except Exception as E:
                    print(E)
            else:
                weather_id = w[0]

            #Venue Search and Collections
		    cursor_shows.execute(venue_query_select.format(row['Venue'].rstrip().strip()))
		    v = cursor_shows.fetchone()

		    if(v is None):
                try:
					cursor_shows.execute(venue_query.format(row['Venue'].rstrip().strip(),80))
                    venue_id = cursor_shows.lastrowid
                    mydb.commit()
                except Exception as E:
                    print(E)
            else:
				venue_id = v[0]


            #Show Search and Insert
			s = row['Date'].rstrip().strip().split('/')
            n = s[2] + '-' + s[0] + '-' + s[1]
            vm = row['Ticket Price'].rstrip().replace(' ','')
            if(vm == ''):
                vm = 0

		    cursor_shows.execute(shows_query_select.format(venue_id, n))
		    s = cursor_shows.fetchone()

		    if(s is None):
                try:
					cursor_shows.execute(shows_query.format(venue_id,n,row['Act(s)'].rstrip().replace(' ',''),
                    weather_id,vm,row['IG'].rstrip().replace(' ',''),row['FB'].rstrip().replace(' ','')
                    ,row['Physical Ad'].rstrip().replace(' ',''),row['Paid Advertisment'].rstrip().replace(' ',''),row['Attendence?'].rstrip().replace(' ','')
                    ,row['Venue Money?'].rstrip().replace(' ','')))
                    show_id = cursor_shows.lastrowid
                    mydb.commit()
                except Exception as E:
                    print(E)
            else:
                show_id = s[0]

            #ShowsBands Table Insert
            cursor_shows.execute(show_band_select.format(show_id, row['Primary ID'].rstrip().strip()))
            c = cursor_shows.fetchone()

            if(c is None):
                try:
                    cursor_shows.execute(show_band_query.format(show_id, row['Primary ID'].rstrip().strip()))
                    mydb.commit()
                except Exception as E:
                    print(E)




        mydb.close()

main()
