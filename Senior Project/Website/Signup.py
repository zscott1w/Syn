#Signup page for my website

#!/usr/bin/env python

import MySQLdb, cgi, re, hashlib
#Data withheld
try:
    db = MySQLdb.connect(host="",    # your host
                         user="",             # your username
                         passwd="",         # your password
                         db="")         # name of the data base
except Exception as E:
    print('Error {}'.format(E))

#SQL Queries and cursors
username_query = "SELECT * from UserID where user_id = '{0}'"
user_insert = "INSERT into UserID(user_id, user_email, user_password) VALUES (%s,%s,%s)"
cursor_select = db.cursor()
cursor_insert = db.cursor()

#CGI form storage
form = cgi.FieldStorage()

username = form.getvalue('username')
email = form.getvalue('email')
password = form.getvalue('password')
has_ran = form.getvalue('ran')

#First time being to page boolean
first = False

#Test if the fields do not exist (yet)
if(email == None):
    email = ""
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
signup_buttons = """<form action = "signup.py" method = "post">
<br>
<p> Email: <input type = "text" name = "email" value="{0}">
<p> Username: <input type = "text" name = "username" value="{1}">
<p> Password:  <input type = password name = "password" value="{2}">
<input type = "hidden" name = "ran" value="{3}">
<p> <input type="submit" value="Confirm">
</form>
<br>
<p> If you already have an account...
<button class="btn" onclick="window.location.href='http://arden.cs.unca.edu/~zboone/login'">Log In</button>""".format(email,username,password,True)

#Valid test booleans
valid_email = False
valid_username = False
valid_password = False

#Tests to see if the fields match the desired values (Regular Expressions)
if(email == ""):
    result = result + "Email must not be blank\n<br>\n"
else:
    if(re.search('\S+@\S+.\S+', email)):
        result = result + ""
        valid_email = True
    else:
        result = result + "Must be a valid email\n<br>\n"

if(username == ""):
    result = result + "Username must not be blank\n<br>\n"
else:
    cursor_select.execute(username_query.format(username.lower()))
    g = cursor_select.fetchone()
    if(g is None):
        result = result + ""
        valid_username = True
    else:
        result = result + "That username has already been taken\n<br>\n"

#Password is stored in hashed format, not plain text
if(password == ""):
    result = result + "Password must not be blank"
else:
    if(len(password) < 8):
        result = result + "Password must be at least 8 characters"
    elif(not(re.search('^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$', password))):
        result = result + "Password must contain at least 1 letter and 1 digit"
    else:
        result = result + ""
        #Library used withheld
        password_hash = hashlib.(password).hexdigest()
        valid_password = True

#If first time visiting, no errors
if(first):
    result = ""

#If you passed all the tests you created an account
if(valid_email and valid_username and valid_password):
    success += "Success!"
    redirect = '<meta charset = "UTF-8" http-equiv="refresh" content="5;url=http://arden.cs.unca.edu/~zboone" />'
    signup_buttons = ""
    cursor_insert.execute(user_insert, (username, email, password_hash))
    db.commit()
    logged_in = True

#HTML script
html = """Content-type: text/html

<!DOCTYPE html>
<html>
<head>
{meta}
<title>Signup Page</title>
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
    background-image: url('pictures/Guitar_Cropped_2.png');
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
.btn {{
    border: none;
}}
.overlay {{
    z-index: 1;
    height: 100%;
    width: 100%;
    position: fixed;
    overflow: auto;
    top: 0px;
    left: 0px;
    background: rgba(0, 0, 0, 0.7);
}}
.error {{
    color: #FF2727
}}
</style>
</head>
<header>
<div class="textbox">
<a href="http://arden.cs.unca.edu/~zboone/">
<img class = "align_left border" src="pictures/Syn_Logo_Black.png" alt="Logo" width="140" height="140">
</a>
<h1 class="fancy_text align_right">Music Marketing</h1>
<div style="clear:both;"></div>
</div>
</header>
<body>
<center>
{success}
{form}
{error}
</center>
</body>
</html>
""".format(error=result, success=success, form=signup_buttons, meta=redirect)

print(html)

#close db
db.close()
