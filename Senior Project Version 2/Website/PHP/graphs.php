//graphs.php
//Zach Boone
//graphs.php is not quite finished yet... but soon!

//Database connection
<?php
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
if(isset($_COOKIE["user"])){
    $temp = $_COOKIE["user"];
    $temp_query = mysqli_query($conn, "SELECT * from UserID where activation_code = '$temp'");
    $row = mysqli_fetch_assoc($temp_query);
    $type = $row["user_type"];
    $affil = $row["affiliation"];
    if($type == "Fan"){
        echo "<h2>Welcome $type!!!</h2>";
    }elseif($type == "Band"){
        echo "<h2>Welcome $type!!!</h2>";
    }else{
        if($affil == NULL){
            echo "<h2> Welcome $type!!!</h2>";
        }else{
            echo "<h2>Welcome $affil!!!</h2>";
            $query = "SELECT * from Venue where venue_name = '$affil'";
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_assoc($result)){
                $id = $row["venue_id"];
                $name = $row["venue_name"];
            }
            $venue_query = "SELECT * from Shows where venue_id = '$id'";
            $venue_query_2 = "SELECT * from Shows where venue_id = '$id' and success_flag = 'Y'";
            $vq = mysqli_query($conn, $venue_query);
            $count = mysqli_num_rows($vq);
            $vq = mysqli_query($conn, $venue_query_2);
            $count_s = mysqli_num_rows($vq);
            $per = round(($count_s/$count)*100, 2);
            echo "<div class='box'>";
            echo "<h2> Number of your shows stored: $count</h2>";
            echo "<h2> Percentage of successful shows: $per%</h2>";
            echo "</div>";
        }
    }
}
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
<div class="box">
<h2> You take control! Compare the success of shows to given values </h2>
<form action = "graphs.php" method = "post">
<br>
<label> X value of the bar graph: </label>
<select id="x" name="x">
    <option value="weather">Weather</option>
    <option value="month">Month</option>
    <option value="genre">Genre</option>
    <option value="demographics">Band Demographics</option>
    <option value="ticketprice">Ticket Price</option>
    <option value="acts">Number of Acts</option>
<input type="submit" value="Confirm" class="accept login">
</form>
<br>
</div>
<div class="graph" id = "post"></div>
<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $choice = $_POST['x'];
    if($choice == "weather"){
        $query = "SELECT weather_id from Shows where success_flag = 'Y'";
        $query2 = "SELECT weather_id from Shows where success_flag = 'N'";
        $query3 = "SELECT weather_type from Weather where weather_id = '{0}'";
        $x_title = "Weather Type";
        $title = "Weather Effects on Show Success";


        $x2 = array();
        $res = mysqli_query($conn, $query);
        while($row = mysqli_fetch_assoc($res)){
            $id = $row['weather_id'];
            $r = mysqli_query($conn, "SELECT weather_type from Weather where weather_id = '$id'");
            $k = mysqli_fetch_assoc($r);
            $x2[] = $k['weather_type'];
        }
        $suc = array_count_values($x2);
        $s = array();
        foreach($suc as $key => $value){
            array_push($s, $value);
        }

        $x3 = array();
        $res = mysqli_query($conn, $query2);
        while($row = mysqli_fetch_assoc($res)){
            $id = $row['weather_id'];
            $r = mysqli_query($conn, "SELECT weather_type from Weather where weather_id = '$id'");
            $k = mysqli_fetch_assoc($r);
            $x3[] = $k['weather_type'];
        }
        $un = array_count_values($x3);
        $u = array();
        foreach($un as $key => $value){
            array_push($u, $value);
        }

        $x = array('Cloudy', 'Rainy', 'Freezing', 'Clear', 'Snowy');

    }
    elseif($choice == 'month'){
        $q = "";
    }
    elseif($choice == 'genre'){
        $q = "";
    }
    elseif($choice == 'ticketprice'){
        $q = "";
    }
    elseif($choice == 'acts'){
        $q = "";
    }
    elseif($choice == 'demographics'){
        $q = "";
    }

}
mysqli_close($conn);
?>
<script>var trace3 = {x: <?php echo json_encode($x); ?>, y: <?php echo json_encode($s); ?>, type: 'bar', name: 'Successful'};
var trace3b = {x: <?php echo json_encode($x); ?>, y: <?php echo json_encode($u); ?>, type: 'bar', name: 'Unsuccessful'};
var data3 = [trace3, trace3b];
var layout3 = {xaxis: {title:<?php echo json_encode($x_title); ?>}, yaxis: {title:'Amount of Shows'}, title:<?php echo json_encode($title); ?>};
Plotly.newPlot('post', data3, layout3);
</script>
</center>
</body>
</html>
