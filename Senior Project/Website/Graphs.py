#!/usr/bin/env python3

from os import environ
import MySQLdb, plotly, cgi
from plotly import graph_objs as go, tools as ts
try:
    db = MySQLdb.connect(host="",    # your host
                         user="",             # your username
                         passwd="",         # your password
                         db="")         # name of the database
except Exception as E:
    print('Error {}'.format(E))

cursor = db.cursor()
b_cursor = db.cursor()

form = cgi.FieldStorage()
x = form.getvalue('x')

plot4 = ""

if(x=='weather'):
    query = "SELECT weather_id from Shows where success_flag = 'Y'"
    query2 = "SELECT weather_id from Shows where success_flag = 'N'"
    query3 = "SELECT weather_type from Weather where weather_id = '{0}'"

    cursor.execute(query)
    y = cursor.fetchall()
    b_cursor.execute(query2)
    n = b_cursor.fetchall()

    weather = {}
    weather2 = {}

    for i in y:
        cursor.execute(query3.format(i[0]))
        c = cursor.fetchone()
        if(c[0] not in weather):
            weather[c[0]] = 0
        try:
            weather[c[0]] = weather[c[0]] + 1
        except Exception as E:
            print('Got exception {}'.format(E))

    for i in n:
        b_cursor.execute(query3.format(i[0]))
        d = b_cursor.fetchone()
        if(d[0] not in weather2):
            weather2[d[0]] = 0
        try:
            weather2[d[0]] = weather2[d[0]] + 1
        except Exception as E:
            print('Got exception {}'.format(E))

    wea_x = ['Cloudy', 'Rainy', 'Freezing', 'Clear', 'Snowy']
    wea_y = [weather['Cloudy'], weather['Rainy'], weather['Freezing'], weather['Clear'], weather['Snowy']]
    wea2_y = [weather2['Cloudy'], weather2['Rainy'], weather2['Freezing'], weather2['Clear'], 0]

    trace4 = go.Bar(x = wea_x,y = wea_y, name="Successful")
    trace4b = go.Bar(x = wea_x,y = wea2_y, name="Unsuccessful")
    data4 = go.Data([trace4, trace4b])
    layout4 =go.Layout(title="Weather effects on a Shows Success", xaxis={'title':'Weather'}, yaxis={'title':'Amount of Shows'})
    figure4 = go.Figure(data = data4, layout = layout4)
    plot4 = plotly.offline.plot(figure4, include_plotlyjs=False, output_type='div')

elif(x=="month"):
    query = "SELECT show_date from Shows where success_flag = 'Y'"
    query2 = "SELECT show_date from Shows where success_flag = 'N'"
    month_get = "SELECT MONTH('{0}')"

    cursor.execute(query)
    m = cursor.fetchall()
    b_cursor.execute(query2)
    n = b_cursor.fetchall()

    jan = 0
    feb = 0
    mar = 0
    apr = 0
    may = 0
    jun = 0
    jul = 0
    aug = 0
    sep = 0
    octo = 0
    nov = 0
    dec = 0
    for i in m:
        cursor.execute(month_get.format(i[0]))
        res = cursor.fetchone()
        if(res[0] == 1):
            jan = jan + 1
        elif(res[0] == 2):
            feb = feb + 1
        elif(res[0] == 3):
            mar = mar + 1
        elif(res[0] == 4):
            apr = apr + 1
        elif(res[0] == 5):
            may = may + 1
        elif(res[0] == 6):
            jun = jun + 1
        elif(res[0] == 7):
            jul = jul + 1
        elif(res[0] == 8):
            aug = aug + 1
        elif(res[0] == 9):
            sep = sep + 1
        elif(res[0] == 10):
            octo = octo + 1
        elif(res[0] == 11):
            nov = nov + 1
        else:
            dec = dec + 1

    mon_x = ['January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
    mon_y = [jan, feb, mar, apr, may, jun, jul, aug, sep, octo, nov, dec]

    jan = 0
    feb = 0
    mar = 0
    apr = 0
    may = 0
    jun = 0
    jul = 0
    aug = 0
    sep = 0
    octo = 0
    nov = 0
    dec = 0
    for i in n:
        b_cursor.execute(month_get.format(i[0]))
        res = b_cursor.fetchone()
        if(res[0] == 1):
            jan = jan + 1
        elif(res[0] == 2):
            feb = feb + 1
        elif(res[0] == 3):
            mar = mar + 1
        elif(res[0] == 4):
            apr = apr + 1
        elif(res[0] == 5):
            may = may + 1
        elif(res[0] == 6):
            jun = jun + 1
        elif(res[0] == 7):
            jul = jul + 1
        elif(res[0] == 8):
            aug = aug + 1
        elif(res[0] == 9):
            sep = sep + 1
        elif(res[0] == 10):
            octo = octo + 1
        elif(res[0] == 11):
            nov = nov + 1
        else:
            dec = dec + 1

    mon2_y =[jan, feb, mar, apr, may, jun, jul, aug, sep, octo, nov, dec]

    trace4 = go.Bar(x = mon_x,y = mon_y, name="Successful")
    trace4b = go.Bar(x = mon_x,y = mon2_y, name="Unsuccessful")
    data4 = go.Data([trace4, trace4b])
    layout4 =go.Layout(title="Show month effect on a Shows Success", xaxis={'title':'Month'}, yaxis={'title':'Amount of Shows'})
    figure4 = go.Figure(data = data4, layout = layout4)
    plot4 = plotly.offline.plot(figure4, include_plotlyjs=False, output_type='div')


elif(x=="genre"):
    query = "SELECT * from Shows where success_flag = 'Y'"
    query2 = "SELECT * from Shows where success_flag = 'N'"
    query3 = "SELECT band_id from Band"
    query4 = "SELECT genre_name from Genre where genre_id = (SELECT genre_id from BandGenres where band_id = '{0}')"

    band_genres = {}
    cursor.execute(query3)
    b = cursor.fetchall()
    for row in b:
        b_cursor.execute(query4.format(row[0]))
        g = b_cursor.fetchone()
        genre = g[0]
        band_genres[row[0]] = genre

    band_genre = []
    for k in sorted(band_genres.keys()):
        print(k, ",", band_genres[k])

elif(x=='ticketprice'):
    query = "SELECT ticket_price from Shows where success_flag = 'Y'"

    cursor.execute(query)
    tp = cursor.fetchall()
    price = {}
    price['0'] = 0
    price['1-10'] = 0
    price['11-15'] = 0
    price['16-20'] = 0
    price['20+'] = 0
    for i in tp:
        if(i[0] == 0):
            price['0'] = price['0'] + 1
        elif(10 >= i[0] > 0):
            price['1-10'] = price['1-10'] + 1
        elif(15>= i[0] > 10):
            price['11-15'] = price['11-15'] + 1
        elif(20>= i[0] > 15):
            price['16-20'] = price['16-20'] + 1
        else:
            price['20+'] = price['20+'] + 1

    tp_x = ['0', '1-10', '11-15', '16-20', '20+']
    tp_y = [price['0'], price['1-10'], price['11-15'], price['16-20'], price['20+']]

    trace4 = go.Bar(x = tp_x, y = tp_y, name="Successful")

    query2 = "SELECT ticket_price from Shows where success_flag = 'N'"

    cursor.execute(query2)
    tp = cursor.fetchall()
    price['0'] = 0
    price['1-10'] = 0
    price['11-15'] = 0
    price['16-20'] = 0
    price['20+'] = 0
    for i in tp:
        if(i[0] == 0):
            price['0'] = price['0'] + 1
        elif(10 >= i[0] > 0):
            price['1-10'] = price['1-10'] + 1
        elif(15>= i[0] > 10):
            price['11-15'] = price['11-15'] + 1
        elif(20>= i[0] > 15):
            price['16-20'] = price['16-20'] + 1
        else:
            price['20+'] = price['20+'] + 1

    tp2_y = [price['0'], price['1-10'], price['11-15'], price['16-20'], price['20+']]

    trace4b = go.Bar(x = tp_x, y = tp2_y, name="Unsuccessful")
    data4 = go.Data([trace4, trace4b])
    layout4 =go.Layout(title="Ticket price effect on a Shows Success", xaxis={'title':'Ticket Price $'}, yaxis={'title':'Amount of Shows'})
    figure4 = go.Figure(data = data4, layout = layout4)
    plot4 = plotly.offline.plot(figure4, include_plotlyjs=False, output_type='div')

elif(x=='demographics'):
    query = ""
else:
    query = ""

cookie_query = "SELECT * from UserID where activation_code = '{0}'"
band_affiliation_query = "SELECT * from Band where band_name = '{0}'"
venue_affiliation_query = "SELECT * from Venue where venue_name = '{0}'"
result = ""
result2 = ""
user_type = 0
handler = {}
if 'HTTP_COOKIE' in environ:
    cookies = environ['HTTP_COOKIE']
    cookies = cookies.split('; ')

    for cookie in cookies:
        cookie = cookie.split('=')
        handler[cookie[0]] = cookie[1]

    user = handler["user"]
    buttons =  """<button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/user_account.php';">Account</button>
        <button class="btn login align_right" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/logout.php';">Sign Out</button>"""
else:
    user = ""
    buttons = """<button class="btn login align_right" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/login.php';">Log In</button>
        <p class="align_right">&nbsp</p>
        <button class="btn signup align_right" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/signup.php';">Sign Up</button>"""

cursor.execute(cookie_query.format(user))
g = cursor.fetchone()
if(g is None):
    result = result + ""
else:
    if(g[3] == "Fan"):
        user_type = 0
    else:
        if(g[3] == "Band"):
            if(g[8] is None):
                user_type = 0
            else:
                user_type = 1
                cursor.execute(band_affiliation_query.format(g[8]))
                current_band = cursor.fetchone()
        else:
            if(g[8] is None):
                user_type = 0
            else:
                user_type = 2
                cursor.execute(venue_affiliation_query.format(g[8]))
                current_venue = cursor.fetchone()

    if(user_type == 1):
        result = "Welcome " + current_band[1] + "!!!"
        band_query = "SELECT * from Shows where show_id = '{0}' and success_flag = 'Y'"
        band_query_2 = "SELECT show_id from ShowsBands where band_id = '{0}'".format(current_band[0])
        cursor.execute(band_query_2)
        v = cursor.fetchall()
        count = len(v)
        count_success = 0
        for i in v:
            b_cursor.execute(band_query.format(i[0]))
            w = b_cursor.fetchone()
            if(w is not None):
                count_success = count_success + 1
        per = round((count_success/count)*100, 2)

        result2 = """<div class="box">
        <h2># of your shows: {0}</h2>
        <h2> % of successful shows: {1}</h2>
        </div>""".format(count, per)
    elif(user_type == 2):
        result = "Welcome " + current_venue[1] + "!!!"
        venue_query = "SELECT * from Shows where venue_id = '{0}'".format(current_venue[0])
        venue_query_2 = "SELECT * from Shows where venue_id = '{0}' and success_flag = 'Y'".format(current_venue[0])
        cursor.execute(venue_query)
        q = cursor.fetchall()
        count = len(q)
        b_cursor.execute(venue_query_2)
        e = b_cursor.fetchall()
        count_s = len(e)
        per = round((count_s/count)*100, 2)

        result2 = """<div class="box">
        <h2># of your shows: {0}</h2>
        <h2> % of successful shows: {1}</h2>
        </div>""".format(count, per)
    else:
        result = "Welcome " + g[3] + "!!!"


band_show = {}
band_successful_show = {}
show_query = "SELECT show_id from Shows"
s_show_query = "SELECT show_id from Shows where success_flag = 'Y'"
b_query = "SELECT band_id from ShowsBands where show_id = '{0}'"

cursor.execute(show_query)
s = cursor.fetchall()
for row in s:
    b_cursor.execute(b_query.format(row[0]))
    y = b_cursor.fetchall()
    for r in y:
        if(r[0] not in band_show):
            band_show[r[0]] = 0
        try:
            band_show[r[0]] = band_show[r[0]] + 1
        except Exception as E:
            print('Got exception {}'.format(E))

band_shows = []
for i in sorted(band_show.keys()) :
     band_shows.append(band_show[i])
     band_show[i] = 0

cursor.execute(s_show_query)
s2 = cursor.fetchall()
for row in s2:
    b_cursor.execute(b_query.format(row[0]))
    y2 = b_cursor.fetchall()
    for r in y2:
        try:
            band_show[r[0]] = band_show[r[0]] + 1
        except Exception as E:
            print('Got exception {}'.format(E))

band_successful_shows=[]
for i in sorted (band_show.keys()) :
     band_successful_shows.append(band_show[i])

success_percent = []
for i in range(len(band_shows)):
    success_percent.append(band_successful_shows[i] / band_shows[i])


show_query = "SELECT show_id from Shows where success_flag = 'Y'"
fb_show_query = "SELECT show_id from Shows where success_flag = 'Y' and fb_flag = 'Y'"
ig_show_query = "SELECT show_id from Shows where success_flag = 'Y' and ig_flag = 'Y'"
phy_show_query = "SELECT show_id from Shows where success_flag = 'Y' and physical_ad_flag = 'Y'"
paid_show_query = "SELECT show_id from Shows where success_flag = 'Y' and paid_ad_flag = 'Y'"
all_query = "SELECT show_id from Shows where success_flag = 'Y' and fb_flag = 'Y' and ig_flag = 'Y' and physical_ad_flag = 'Y' and paid_ad_flag = 'Y'"
none_query = "SELECT show_id from Shows where success_flag = 'Y' and fb_flag = 'N' and ig_flag = 'N' and physical_ad_flag = 'N' and paid_ad_flag = 'N'"
sho_query = "SELECT show_id from Shows where success_flag = 'N'"
f_show_query = "SELECT show_id from Shows where success_flag = 'N' and fb_flag = 'Y'"
i_show_query = "SELECT show_id from Shows where success_flag = 'N' and ig_flag = 'Y'"
ph_show_query = "SELECT show_id from Shows where success_flag = 'N' and physical_ad_flag = 'Y'"
pai_show_query = "SELECT show_id from Shows where success_flag = 'N' and paid_ad_flag = 'Y'"
al_query = "SELECT show_id from Shows where success_flag = 'N' and fb_flag = 'Y' and ig_flag = 'Y' and physical_ad_flag = 'Y' and paid_ad_flag = 'Y'"
non_query = "SELECT show_id from Shows where success_flag = 'N' and fb_flag = 'N' and ig_flag = 'N' and physical_ad_flag = 'N' and paid_ad_flag = 'N'"


cursor.execute(show_query)
h = cursor.fetchall()
a = len(h)
cursor.execute(fb_show_query)
fb = cursor.fetchall()
b = len(fb)
cursor.execute(ig_show_query)
ig = cursor.fetchall()
c = len(ig)
cursor.execute(phy_show_query)
p = cursor.fetchall()
d = len(p)
cursor.execute(paid_show_query)
pa = cursor.fetchall()
e = len(pa)
cursor.execute(all_query)
al = cursor.fetchall()
f = len(al)
cursor.execute(none_query)
n = cursor.fetchall()
g = len(n)

cursor.execute(sho_query)
h2= cursor.fetchall()
i = len(h2)
cursor.execute(f_show_query)
fb2 = cursor.fetchall()
j = len(fb2)
cursor.execute(i_show_query)
ig2 = cursor.fetchall()
k = len(ig2)
cursor.execute(ph_show_query)
p2 = cursor.fetchall()
l = len(p2)
cursor.execute(pai_show_query)
pa2 = cursor.fetchall()
m = len(pa2)
cursor.execute(al_query)
al2 = cursor.fetchall()
ni = len(al2)
cursor.execute(non_query)
n2 = cursor.fetchall()
o = len(n2)


gra2_y = [i,j,k,l,m,ni,o]

gra_y = [a,b,c,d,e,f,g]
gra_x = ['Overall', 'Facebook', 'Instagram', 'Physical Ads', 'Paid Ads', 'All Media', 'No Media']


genres = {}
band_query = "SELECT band_id from Band where location_id = (SELECT location_id from Location where city = 'Asheville' and state = 'NC')"
genre_query = "SELECT genre_name from Genre where genre_id = (SELECT genre_id from BandGenres where band_id = '{0}' )"
cursor.execute(band_query)
ashe_bands = cursor.fetchall()
for row in ashe_bands:
    b_cursor.execute(genre_query.format(row[0]))
    g = b_cursor.fetchone()
    genre = g[0]
    if(genre is None):
        genre = "None"
    else:
        if(genre not in genres):
            genres[genre] = 0
        try:
            genres[genre] += 1
        except Exception as E:
                print('Got exception {}'.format(E))

genre_x = []
genre_y = []
for k in genres.keys():
    genre_x.append(k)
    genre_y.append(genres[k])


db.close()

trace1 = go.Scatter(x=band_shows, y=success_percent, mode="markers")
data=go.Data([trace1])
layout = go.Layout(title="Band Success rate", xaxis={'title':'Amount of Shows Played'}, yaxis={'title':'Successful Show %'})
figure = go.Figure(data=data,layout=layout)
plot = plotly.offline.plot(figure, include_plotlyjs=False, output_type='div')

trace2 = go.Pie(labels=genre_x, values=genre_y, name = 'Genres')
data2 = go.Data([trace2])
layout2 = go.Layout(title="Genre breakdown of Asheville area bands")
figure2 = go.Figure(data = data2, layout=layout2)
plot2 =  plotly.offline.plot(figure2, include_plotlyjs=False, output_type='div')

trace3 = go.Bar(x = gra_x,y = gra_y, name="Successful")
trace3b = go.Bar(x = gra_x,y = gra2_y, name="Unsuccessful")
data3 = go.Data([trace3, trace3b])
layout3 =go.Layout(title="Social Media/Advertising effects on a Shows Success", xaxis={'title':'Social Media'}, yaxis={'title':'Amount of Shows'})
figure3 = go.Figure(data = data3, layout = layout3)
plot3 = plotly.offline.plot(figure3, include_plotlyjs=False, output_type='div')

html = """Content-type: text/html

<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <title>Syn Graphs</title>
    <link rel="stylesheet" type="text/css" href="css/graphs.css">
    <link rel="icon" href="pictures/Syn_Icon_White.png">
</head>
<body>
<header>
    <div class="textbox">
        <a href="http://arden.cs.unca.edu/~zboone/">
        <img class = "align_left border" src="pictures/Syn_Logo_Black.png" alt="Logo" width="140" height="140">
        </a>
        <h1 class="fancy_text align_right">Music Marketing</h1>
        <div style="clear:both;"></div>
    </div>
<button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/';">Home</button>
<button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/calendar.php';">Calendar</button>
<button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/graphs.py';">Graphs</button>
<button class = "btn signup" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/about.php';">About</button>
{2}
</header>
<center><h2 class="larger_text">Graphs about the Asheville area music scene</h2>
<h2>{1}</h2>
{5}
<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<div class="graph">{0}</div>
<div class="graph">{4}</div>
<div class="graph">{3}</div>
<br>
<div class="box">
<h2> You take control! Compare the success of shows to given values </h2>
<form action = "graphs.py" method = "post">
<br>
<label> X value of the bar graph: </label>
<select id="x" name="x">
    <option value="weather">Weather</option>
    <option value="month">Month</option>
    <option value="genre">Genre</option>
    <option value="demographics">Band Demographics</option>
    <option value="ticketprice">Ticket Price</option>
    <option value="acts">Number of Acts</option>
<input type="submit" value="Confirm">
</form>
<br>
</div>
<div class="graph">{6}</div>
</center>
</body>
</html>
""".format(plot2, result, buttons, plot, plot3, result2, plot4)

print(html)
