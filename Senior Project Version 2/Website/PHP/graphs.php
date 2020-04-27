<?php
//graphs.php | Zachary Boone | 4/26/2020
//Graphical representation of what data is currently in our database

//Connect to database
include("php/database_conn.php");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <title>Syn Graphs</title>
    <link rel="stylesheet" type="text/css" href="css/graphs.css">
    <link rel="icon" href="pictures/Syn_Icon_White.png">
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
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
    <button class="btn login" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/graphs.php';">Graphs</button>
    <button class = "btn signup" onclick="window.location.href = 'http://arden.cs.unca.edu/~zboone/about.php';">About</button>
    <?php
    if(!isset($_COOKIE["user"])){
        echo "<button class=\"btn login align_right\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/login.php';\">Log In</button>";
        echo "<p class=\"align_right\">&nbsp</p>";
        echo "<button class=\"btn signup align_right\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/signup.php';\">Sign Up</button>";
    }else{
        echo "<button class=\"btn login\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/user_account.php';\">Account</button>";
        echo "<button class=\"btn login align_right\" onclick=\"window.location.href = 'http://arden.cs.unca.edu/~zboone/logout.php';\">Sign Out</button>";
    }
    ?>
</header>
<center>
<h2 class="larger_text">Graphs about the Asheville area music scene</h2>
<?php

//If user is currently logged in
if(isset($_COOKIE["user"])){

    //Get userID information from cookie value
    $temp = $_COOKIE["user"];
    $temp_query = mysqli_query($conn, "SELECT user_type, affiliation from UserID where activation_code = '$temp'");
    $row = mysqli_fetch_assoc($temp_query);
    $type = $row["user_type"];
    $affil = $row["affiliation"];

    //Greeting changes based on what type of user they are
    //Fan -> Just welcome
    //Band ->
    //      Not affiliated with a band in the database means just welcome
    //      Affiliated with a band in the database displays some of their information
    //Venue ->
    //      Not affiliated with a venue -> just welcome
    //      Affiliated with a venue displays more information
    if($type == "Fan"){
        echo "<h2>Welcome $type!!!</h2>";
    }elseif($type == "Band"){

        if($affil == NULL){
             echo "<h2>Welcome $type!!!</h2>";
        }else{

            echo "<h2>Welcome $affil!!!</h2>";

            //Find band in database
            $query = "SELECT band_id, band_name from Band where band_name = '$affil'";
            $result = mysqli_query($conn, $query);
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    $id = $row["band_id"];
                    $name = $row["band_name"];
                }
            }


            //Count shows and successful shows to display total amount of shows and success rate
            $count = 0;
            $successful_count = 0;

            $show_query = "SELECT show_id from ShowsBands where band_id = '$id'";
            $sq = mysqli_query($conn, $show_query);
            if(mysqli_num_rows($sq) > 0){
                while($row = mysqli_fetch_assoc($sq)){
                    $sid = $row["show_id"];
                    $show_query_2 = "SELECT success_flag from Shows where show_id = '$sid'";
                    $sq2 = mysqli_query($conn, $show_query_2);
                    if(mysqli_num_rows($sq2) > 0){
                        $r = mysqli_fetch_assoc($sq2);
                        if($r["success_flag"] == 'Y'){
                            $successful_count = $successful_count + 1;
                        }
                    }
                }
                $count = mysqli_num_rows($sq);
                $per = round(($successful_count/$count)*100, 2);
            }else{
                $count = 0;
                $per = 0;
            }
        //Display info
        echo "<div class='box'>";
        echo "<h2> Number of your shows stored: $count</h2>";
        echo "<h2> Percentage of successful shows: $per%</h2>";
        echo "</div>";
        }

    }else{
        //if type == venue;
        if($affil == NULL){
            echo "<h2> Welcome $type!!!</h2>";
        }else{

            echo "<h2>Welcome $affil!!!</h2>";

            //Find venue in database
            $query = "SELECT venue_id, venue_name from Venue where venue_name = '$affil'";
            $result = mysqli_query($conn, $query);
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    $id = $row["venue_id"];
                    $name = $row["venue_name"];
                }
            }

            //Find count of shows and successful show percentage
            $venue_query = "SELECT show_id from Shows where venue_id = '$id'";
            $venue_query_2 = "SELECT show_id from Shows where venue_id = '$id' and success_flag = 'Y'";
            $vq = mysqli_query($conn, $venue_query);
            if(mysqli_num_rows($vq) > 0){
                $count = mysqli_num_rows($vq);
            }else{
                $count = 0;
            }
            $vq = mysqli_query($conn, $venue_query_2);
            if(mysqli_num_rows($vq) > 0){
                $count_s = mysqli_num_rows($vq);
                $per = round(($count_s/$count)*100, 2);
            }else{
                $per = 0;
            }
            //display info
            echo "<div class='box'>";
            echo "<h2> Number of your shows stored: $count</h2>";
            echo "<h2> Percentage of successful shows: $per%</h2>";
            echo "</div>";
        }
    }
}

//Plotly pie chart in Javascript
//Graph that displays the genre distribution (in percentages) of bands in Asheville
//Compile all info into arrays then convert over to javascript objects so they can be displayed in the graph

$band_query = "SELECT band_id from Band where location_id = (SELECT location_id from Location where city = 'Asheville' and state = 'NC')";
$genre_query = "SELECT genre_name from Genre where genre_id = (SELECT genre_id from BandGenres where band_id = '')";

$bq = mysqli_query($conn, $band_query);
$x1 = array();
while($row = mysqli_fetch_assoc($bq)){
    $r = $row['band_id'];
    $res = mysqli_query($conn, "SELECT genre_name from Genre where genre_id = (SELECT genre_id from BandGenres where band_id = '$r')");
    $a = mysqli_fetch_assoc($res);
    $x1[] = $a['genre_name'];
}
$pie = array_count_values($x1);
$genre = array();
$val = array ();
foreach($pie as $key => $value){
    array_push($genre, $key);
    array_push($val, $value);
}


//Plotly bar graph in Javascript
//Graph that displays the comparision of social media's affects on how a successful a show was
//Compile values into two arrays so two different traces can be used

$show_query = "SELECT show_id from Shows where success_flag = 'Y'";
$fb_show_query = "SELECT show_id from Shows where success_flag = 'Y' and fb_flag = 'Y'";
$ig_show_query = "SELECT show_id from Shows where success_flag = 'Y' and ig_flag = 'Y'";
$phy_show_query = "SELECT show_id from Shows where success_flag = 'Y' and physical_ad_flag = 'Y'";
$paid_show_query = "SELECT show_id from Shows where success_flag = 'Y' and paid_ad_flag = 'Y'";
$all_query = "SELECT show_id from Shows where success_flag = 'Y' and fb_flag = 'Y' and ig_flag = 'Y' and physical_ad_flag = 'Y' and paid_ad_flag = 'Y'";
$none_query = "SELECT show_id from Shows where success_flag = 'Y' and fb_flag = 'N' and ig_flag = 'N' and physical_ad_flag = 'N' and paid_ad_flag = 'N'";
$sho_query = "SELECT show_id from Shows where success_flag = 'N'";
$f_show_query = "SELECT show_id from Shows where success_flag = 'N' and fb_flag = 'Y'";
$i_show_query = "SELECT show_id from Shows where success_flag = 'N' and ig_flag = 'Y'";
$ph_show_query = "SELECT show_id from Shows where success_flag = 'N' and physical_ad_flag = 'Y'";
$pai_show_query = "SELECT show_id from Shows where success_flag = 'N' and paid_ad_flag = 'Y'";
$al_query = "SELECT show_id from Shows where success_flag = 'N' and fb_flag = 'Y' and ig_flag = 'Y' and physical_ad_flag = 'Y' and paid_ad_flag = 'Y'";
$non_query = "SELECT show_id from Shows where success_flag = 'N' and fb_flag = 'N' and ig_flag = 'N' and physical_ad_flag = 'N' and paid_ad_flag = 'N'";

$success = array();
$unsuccess = array();

array_push($success, mysqli_num_rows(mysqli_query($conn, $show_query)));
array_push($success, mysqli_num_rows(mysqli_query($conn, $fb_show_query)));
array_push($success, mysqli_num_rows(mysqli_query($conn, $ig_show_query)));
array_push($success, mysqli_num_rows(mysqli_query($conn, $phy_show_query)));
array_push($success, mysqli_num_rows(mysqli_query($conn, $paid_show_query)));
array_push($success, mysqli_num_rows(mysqli_query($conn, $all_query)));
array_push($success, mysqli_num_rows(mysqli_query($conn, $none_query)));
array_push($unsuccess, mysqli_num_rows(mysqli_query($conn, $sho_query)));
array_push($unsuccess, mysqli_num_rows(mysqli_query($conn, $f_show_query)));
array_push($unsuccess, mysqli_num_rows(mysqli_query($conn, $i_show_query)));
array_push($unsuccess, mysqli_num_rows(mysqli_query($conn, $ph_show_query)));
array_push($unsuccess, mysqli_num_rows(mysqli_query($conn, $pai_show_query)));
array_push($unsuccess, mysqli_num_rows(mysqli_query($conn, $al_query)));
array_push($unsuccess, mysqli_num_rows(mysqli_query($conn, $non_query)));

$labels = array("Overall", "Facebook", "Instagram", "Physical Ads", "Paid Ads", "All Media", "No Media");

?>
<!--
    Plotly graph notation to display using javascript only
    PHP arrays must be converted to json
-->
<div class="graph" id = "ashe_genre"></div>
<script>var trace2 = {labels: <?php echo json_encode($genre); ?>, values: <?php echo json_encode($val); ?>, type: 'pie'};
var data2 = [trace2];
var layout2 = {title:'Genre breakdown of Asheville area bands'};
Plotly.newPlot('ashe_genre', data2, layout2);
</script>
<div class="graph" id = "sma"></div>
<script>var trace = {x: <?php echo json_encode($labels); ?>, y: <?php echo json_encode($success); ?>, type: 'bar', name: 'Successful'};
var traceb = {x: <?php echo json_encode($labels); ?>, y: <?php echo json_encode($unsuccess); ?>, type: 'bar', name: 'Unsuccessful'};
var data = [trace, traceb];
var layout = {xaxis: {title:'Advertising Type'}, yaxis: {title:'Amount of Shows'}, title:'Social Media/Advertising Effects on a Shows Success'};
Plotly.newPlot('sma', data, layout);
</script>
<!--
    Form that allows the user to create graphs on the webpage
    Given a few predetermined selections
-->
<div class="box">
<h2> You take control! Compare the success of shows to given values </h2>
<form action = "graphs.php" method = "post">
<br>
<label> X value of the bar graph: </label>
<select id="x" name="x">
    <option value="weather">Weather</option>
    <option value="month">Month</option>
    <option value="genre">Genre</option>
    <option value="ticketprice">Ticket Price</option>
    <option value="acts">Number of Acts</option>
<input type="submit" value="Confirm" class="accept login">
</form>
<br>
</div>
<div class="graph" id = "post"></div>
<?php
//Initalization of common variables used for graphing
$x = array();
$s = array();
$u = array();
$title = "";
$x_title = "";
$t1_name = "";
$t2_name = "";

//Once the form is submitted, then decide which value to graph
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $choice = $_POST['x'];

    //Each of these were like a puzzle or naviagating a spider web
    //1st option -> Success vs. Weather
    if($choice == "weather"){

        //Graph display variables
        $x_title = "Weather Type";
        $title = "Weather Effects on Asheville Show Success";
        $t1_name = "Successful";
        $t2_name = "Unseccessful";

        //Queries
        $query = "SELECT weather_id from Shows where success_flag = 'Y'";
        $query2 = "SELECT weather_id from Shows where success_flag = 'N'";
        $query3 = "SELECT weather_type from Weather where weather_id = '{0}'";

        //Get successful shows from the database and convert them to an array
        //Sort by key to keep values consistent
        $x2 = array();
        $res = mysqli_query($conn, $query);

        while($row = mysqli_fetch_assoc($res)){
            $id = $row['weather_id'];
            $r = mysqli_query($conn, "SELECT weather_type from Weather where weather_id = '$id'");
            $k = mysqli_fetch_assoc($r);
            $x2[] = $k['weather_type'];
        }

        $suc = array_count_values($x2);
        ksort($suc);

        foreach($suc as $key => $value){
            array_push($s, $value);
            array_push($x, $key);
        }

        //Get unsuccessful shows from the database and convert them to an array
        $x3 = array();
        $res = mysqli_query($conn, $query2);

        while($row = mysqli_fetch_assoc($res)){
            $id = $row['weather_id'];
            $r = mysqli_query($conn, "SELECT weather_type from Weather where weather_id = '$id'");
            $k = mysqli_fetch_assoc($r);
            $x3[] = $k['weather_type'];
        }

        $un = array_count_values($x3);
        ksort($un);

        foreach($un as $key => $value){
            array_push($u, $value);
        }

    }
    //2nd option -> Success based on Month
    elseif($choice == 'month'){

        //Graph display variables
        $x_title = "Month";
        $title = "Asheville Show Successes By Month";
        $t1_name = "Successful";
        $t2_name = "Unseccessful";
        $x = array('January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');


        //Query
        $query = "SELECT MONTH(show_date), success_flag from Shows";

        //Find success/unsuccess based on month
        $res = mysqli_query($conn, $query);
        $s = array_fill(0, 12, 0);
        $u = array_fill(0, 12, 0);

        if(mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $month = $row["MONTH(show_date)"];
                $sf = $row["success_flag"];

                if($sf == 'Y'){
                    $s[$month - 1] += 1;
                }else{
                    $u[$month - 1] += 1;
                }

            }
        }
    }
    //3rd option -> Success based on genre
    elseif($choice == 'genre'){

        //Graph display variables
        $x_title = "Genre";
        $title = "Asheville Show Successes By Genre";
        $t1_name = "Successful";
        $t2_name = "Unseccessful";

        //Queries
        $query = "SELECT show_id from Shows where success_flag = 'Y'";
        $query2 = "SELECT show_id from Shows where success_flag = 'N'";
        $query3 = "SELECT band_id from Band";

        //Initalize
        $s_shows = mysqli_query($conn, $query);
        $u_shows = mysqli_query($conn, $query2);
        $x1 = array();
        $x2 = array();

        //Compile successful shows
        if(mysqli_num_rows($s_shows) > 0){
            while($row = mysqli_fetch_assoc($s_shows)){

                $sid = $row["show_id"];
                $bquery = "SELECT band_id from ShowsBands where show_id = '$sid'";
                $bq = mysqli_query($conn, $bquery);

                while($b = mysqli_fetch_assoc($bq)){

                    $bid = $b["band_id"];
                    $gquery = "SELECT genre_name from Genre where genre_id = (SELECT genre_id from BandGenres where band_id = '$bid')";
                    $gq = mysqli_query($conn, $gquery);
                    $g = mysqli_fetch_assoc($gq);
                    $genre = $g["genre_name"];

                    if(array_key_exists($genre, $x1)){
                        $x1[$genre] += 1;
                    }else{
                        $x1[$genre] = 1;
                    }
                }
            }
        }
        //Compile unsuccessful shows
        if(mysqli_num_rows($u_shows) > 0){
            while($row = mysqli_fetch_assoc($u_shows)){

                $sid = $row["show_id"];
                $bquery = "SELECT band_id from ShowsBands where show_id = '$sid'";
                $bq = mysqli_query($conn, $bquery);

                while($b = mysqli_fetch_assoc($bq)){

                    $bid = $b["band_id"];
                    $gquery = "SELECT genre_name from Genre where genre_id = (SELECT genre_id from BandGenres where band_id = '$bid')";
                    $gq = mysqli_query($conn, $gquery);
                    $g = mysqli_fetch_assoc($gq);
                    $genre = $g["genre_name"];

                    if(array_key_exists($genre, $x1)){
                        $x2[$genre] += 1;
                    }else{
                        $x2[$genre] = 1;
                    }
                }
            }
        }

        //Sort arrays by keys and then push to display arrays
        ksort($x1);
        ksort($x2);
        foreach($x1 as $key => $value){
            array_push($s, $value);
            array_push($x, $key);
        }
        foreach($x2 as $key => $value){
            array_push($u, $value);
        }
    }
    //4th option -> Success based on ticketprice
    elseif($choice == 'ticketprice'){

        //Display variables
        $x_title = "Ticket Price in US Dollars($)";
        $title = "Asheville Show Successes By Ticket Price";
        $t1_name = "Successful";
        $t2_name = "Unseccessful";
        $x = array('0', '1-10', '11-15', '16-20', '21+');

        //Queries
        $query = "SELECT ticket_price from Shows where success_flag = 'Y'";
        $query2 = "SELECT ticket_price from Shows where success_flag = 'N'";

        //Initilize
        $q = mysqli_query($conn, $query);
        $q2 = mysqli_query($conn, $query2);
        $sq = array('0' => 0, '1-10' => 0, '11-15' => 0, '16-20' => 0, '21+' => 0);
        $sq2 = array('0' => 0, '1-10' => 0, '11-15' => 0, '16-20' => 0, '21+' => 0);

        //Successful shows
        if(mysqli_num_rows($q) > 0){
            while($row = mysqli_fetch_assoc($q)){
                $cost = $row["ticket_price"];
                if($cost == NULL){
                    continue;
                }
                elseif($cost == 0){
                    $sq['0'] += 1;
                }
                elseif(10 >= $cost and $cost > 0){
                    $sq['1-10'] += 1;
                }
                elseif(15 >= $cost and $cost > 10){
                    $sq['11-15'] += 1;
                }
                elseif(20 >= $cost and $cost > 16){
                    $sq['16-20'] += 1;
                }
                elseif($cost > 20){
                    $sq['21+'] += 1;
                }
            }
        }

        //Unsuccessful shows
        if(mysqli_num_rows($q2) > 0){
            while($row = mysqli_fetch_assoc($q2)){
                $cost = $row["ticket_price"];
                if($cost == NULL){
                    continue;
                }
                elseif($cost == 0){
                    $sq2['0'] += 1;
                }
                elseif(10 >= $cost and $cost > 0){
                    $sq2['1-10'] += 1;
                }
                elseif(15 >= $cost and $cost > 10){
                    $sq2['11-15'] += 1;
                }
                elseif(20 >= $cost and $cost > 16){
                    $sq2['16-20'] += 1;
                }
                else{
                    $sq2['21+'] += 1;
                }
            }
        }

        //create display arrays
        foreach($sq as $key => $value){
            array_push($s, $value);
        }
        foreach($sq2 as $key => $value){
            array_push($u, $value);
        }
    }
    //5th option -> Success based on number of acts (ie. 2 bands in a show = 2 acts)
    //$choice == 'acts';
    else{

        //Display variables
        $x_title = "Number of Acts";
        $title = "Show Successes By Number of Acts in a Show";
        $t1_name = "Successful";
        $t2_name = "Unseccessful";
        $x = array('1', '2', '3', '4', '5+');

        //Queries
        $query = "SELECT number_of_acts, success_flag from Shows";

        //Inialize
        $s = array_fill(0, 5, 0);
        $u = array_fill(0, 5, 0);

        $res_query = mysqli_query($conn, $query);
        if(mysqli_num_rows($res_query) > 0){
            while($row = mysqli_fetch_assoc($res_query)){
                $noa = $row["number_of_acts"];
                $sf = $row["success_flag"];
                //Compile successful shows
                //else -> unsuccessful
                if($sf == 'Y'){
                    if($noa == 0 or $noa == NULL){
                        continue;
                    }elseif($noa == 1){
                        $s[0] += 1;
                    }elseif($noa == 2){
                        $s[1] += 1;
                    }elseif($noa == 3){
                        $s[2] += 1;
                    }elseif($noa == 4){
                        $s[3] += 1;
                    }else{
                        $s[4] += 1;
                    }
                }else{
                    if($noa == 0 or $noa == NULL){
                        continue;
                    }elseif($noa == 1){
                        $u[0] += 1;
                    }elseif($noa == 2){
                        $u[1] += 1;
                    }elseif($noa == 3){
                        $u[2] += 1;
                    }elseif($noa == 4){
                        $u[3] += 1;
                    }else{
                        $u[4] += 1;
                    }
                }
            }
        }
    }
}
mysqli_close($conn);
?>
<!-- Display interactive graph -->
<script>var trace3 = {x: <?php echo json_encode($x); ?>, y: <?php echo json_encode($s); ?>, type: 'bar', name: <?php echo json_encode($t1_name); ?>};
var trace3b = {x: <?php echo json_encode($x); ?>, y: <?php echo json_encode($u); ?>, type: 'bar', name: <?php echo json_encode($t2_name); ?>};
var data3 = [trace3, trace3b];
var layout3 = {xaxis: {title:<?php echo json_encode($x_title); ?>}, yaxis: {title:'Amount of Shows'}, title:<?php echo json_encode($title); ?>};
Plotly.newPlot('post', data3, layout3);
</script>
</center>
</body>
</html>
