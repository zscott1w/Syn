#Login page for my website

#!/usr/bin/env python

import MySQLdb, cgi, hashlib
#Data withheld on database
try:
    db = MySQLdb.connect(host="",    # your host
                         user="",             # your username
                         passwd="",         # your password
                         db="")         # name of the data base
except Exception as E:
    print('Error {}'.format(E))

#SQL Queries and cursor
username_query = "SELECT * from UserID where user_id = '{0}'"
user_insert = "SELECT * from UserID where password = '{0}'"
cursor = db.cursor()

#CGI form storage and values
form = cgi.FieldStorage()

username = form.getvalue('username')
password = form.getvalue('password')
has_ran = form.getvalue('ran')

#First time visiting login should not give errors
first = False

#Test if fields are empty
if(username == None):
    username = ""
if(password == None):
    password = ""
if(has_ran == None):
    first = True

#DOM modification strings
result = '<p class="error">'
success = "<p>"
redirect = '<meta charset = "UTF-8">'
signup_buttons = """<form action = "login.py" method = "post">
<br>
<p> Username: <input type = "text" name = "username" value="{0}">
<p> Password:  <input type = password name = "password" value="{1}">
<input type = "hidden" name = "ran" value="{2}">
<p> <input type="submit" value="Confirm">
</form>
<br>
<p> If you do not have an account...
<button class="btn" onclick="window.location.href='http://arden.cs.unca.edu/~zboone/signup'">Create Account</button>
<br>""".format(username, password, True)

#If passes both booleans then you Login
valid_username = False
valid_password = False

#Testing and SQL Queries to see if username exists and matches the password in the DB
if(username == ""):
    result = result + "Must enter a username"
else:
    cursor.execute(username_query.format(username.lower()))
    g = cursor.fetchone()
    if(g is None):
        result = result + "That username does not exist\n<br>\n"
    else:
        valid_username = True
        result = result + ""
    if(password == ""):
        result = result + "Must enter a password"
    else:
        #Which library I used is withheld
        match = hashlib.(password).hexdigest()
        if(match == g[2]):
            success = success + "Successful Login!"
            signup_buttons = ""
            redirect = '<meta charset = "UTF-8" http-equiv="refresh" content="4;url=http://arden.cs.unca.edu/~zboone" />'
            result = result + ""
            valid_password = True
        else:
            result = result + "Password incorrect"

#Remove error if first time visiting the Page
if(first):
    result = ""

#If both are true then you login
if(valid_username and valid_password):
    logged_in = True

#HTML script
html = """Content-type: text/html

<!DOCTYPE html>
<html>
<head>
{meta}
<title>Login Page</title>
<link rel="icon" href="pictures/Syn_Icon_White.png">
<style>
html, body, h1, div, span{{
    margin: 0;
    padding: 0;
    border: 0;
    font-size: 100%;
    font-family: calibri, sans-serif;
    color: #FFFFFF;
}}
p{{
    margin: 0;
    padding: 0;
    border: 0;
    font-size: 100%;
    font-family: calibri, sans-serif;
    color: #FFFFFF;
}}
body{{
    background-color: #333333;
    background-image: url('pictures/Drums_Cropped_2.png');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
}}
header{{
    display: block;
    background-color: #000000;
}}
img{{
    background-color: #111111;
}}
.btn {{
    border: none;
    background-color: #FFFFFF;
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
.error {{
    color: #FF2727
}}
</style>
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
</header>
<center>
{success}
{form}
{error}
</center>
</body>
</html>""".format(meta=redirect, success=success, form=signup_buttons, error=result)

print(html)

#Close db
db.close()
