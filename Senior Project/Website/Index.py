#Example webpage to show ability to connect to database and modify the DOM with
#python

#!/usr/bin/env python

import MySQLdb
try:
    #Data withheld
    db = MySQLdb.connect(host="",    # your host
                         user="",             # your username
                         passwd="",         # your password
                         db="")         # name of the data base
except Exception as E:
    print('Error {}'.format(E))

cursor = db.cursor()
result = ""
table = """    <tr>
        <td>{0}</td>
        <td>{1}</td>
        <td>{2}</td>
    </tr>\n"""

#Join of two tables (Band, Location)
cursor.execute("SELECT Band.band_name, Location.city, Band.member_count From Band, Location where Band.location_id = Location.location_id")
dbr = cursor.fetchall()
#Format the data to the table row
for i in dbr:
    result += table.format(i[0], i[1], i[2])

db.close()

#Python CGI
#Inline CSS because downloading it from another file was not working correctly
html = """Content-type: text/html

<!DOCTYPE html>
<html>
<head>
<meta charset = "UTF-8">
<title>Syn : Music Marketing</title>
<link rel="icon" href="pictures/Syn_Icon_White.png">
<style>
html, body, div, h1, span, p{{
    margin: 0;
    padding: 0;
    border: 0;
    font-size: 100%;
    font-family: calibri, sans-serif;
}}
body{{
    background-color: #333333;
}}
header{{
    display: block;
    background-color: #000000;
}}
img{{
    background-color: #111111;
}}
h1, h2, p, span{{
    color: #FFFFFF;
}}
table {{
  font-family: calibri, sans-serif;
  border-collapse: collapse;
  width: 100%;
  font-weight: bold;
  color: #FFFFFF;
}}
td, th {{
  border: 2px solid #000000;
  text-align: left;
  padding: 8px;
}}
tr:nth-child(even) {{
  background-color: #dddddd;
  color: #000000
}}
.textbox {{
    display: flex;
}}
.textbox h1 {{
    margin: auto;
    vertical-align: top;
}}
.align_left {{
    float: left;
}}
.align_right {{
    float: right;
}}
.larger_text {{
    font-size: 190%;
}}
.fancy_text {{
    font-size: 400%;
    font-family: "Brush Script MT", cursive;
}}
.border {{
    border-width: 17px;
    border-color: #000000;
    border-style: solid;
}}
</style>
</head>
<body>
<header>
<div class="textbox">
<a href="http://arden.cs.unca.edu/~zboone/graph">
<img class = "align_left border" src="pictures/Syn_Logo_Black.png" alt="Logo" width="140" height="140">
</a>
<h1 class="fancy_text align_right">Music Marketing</h1>
<div style="clear:both;"></div>
</div>
</header>
<table>
    <tr class="larger_text">
        <th>Band Name</th>
        <th>Hometown</th>
        <th># of Band Members</th>
    </tr>
{DB}
</table>
</body>
</html>
""".format(DB=result)
#Print Webpage
print(html)
