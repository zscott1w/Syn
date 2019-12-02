#Example page to show ability to represent plotly graphs
#on a webpage

#!/usr/bin/env python3

import MySQLdb, plotly
#plotly 3 syntax
from plotly import graph_objs as go, tools as ts
try:
    #Data withheld
    db = MySQLdb.connect(host="",    # your host
                         user="",    # your username
                         passwd="",  # your password
                         db="")      # name of the data base
except Exception as E:
    print('Error {}'.format(E))

cursor = db.cursor()
db.close()

#Scatter Plot
trace1 = go.Scatter(x=[1,2,3], y=[4,5,6], marker={'color': 'red', 'symbol': 104, 'size': "10"},
                    mode="markers+lines",  text=["one","two","three"], name='1st Trace')
data=go.Data([trace1])
layout=go.Layout(title="First Plot", xaxis={'title':'x1'}, yaxis={'title':'x2'})
figure=go.Figure(data=data,layout=layout)
plot = plotly.offline.plot(figure, include_plotlyjs=False, output_type='div')

#Pie Chart
labels = ['Oxygen', 'Hydrogen', 'Carbon Dioxide', 'Nitrogen']
values = [4500, 2500, 1053, 500]
trace2 = go.Pie(labels=labels, values=values)
data2 = go.Data([trace2])
layout2 = 0
figure2 = go.Figure(data = data2)
plot2 =  plotly.offline.plot(figure2, include_plotlyjs=False, output_type='div')

#Python CGI
#Inline CSS because downloading it from another file was not working correctly
html = """Content-type: text/html

<!DOCTYPE html>
<html>
<head>
<meta charset = "UTF-8">
<style>
html{{
    margin: 0;
    padding: 0;
    border: 0;
    font-size: 100%;
    font-family: calibri, sans-serif;
}}
header{{
    display: block;
    background-color: #000;
}}
</style>
</head>
<body>
<header>
<a href="http://arden.cs.unca.edu/~zboone/">
<img src="pictures/Syn_Logo_Black.png" alt="Logo" width="250" height="250">
</a>
</header>
<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
{0}
{1}
</body>
</html>
""".format(plot, plot2)
#Print the webpage
print(html)
