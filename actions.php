<?php
include("tool/functions.php");

$error = "";
if ($_GET["action"] == "login") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    if (!$email) {
        $error = "Email id is empty!";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email id is invalid!";
    }
    if (!$password) {
        $error = "Password cannot be empty!";
    }
    if ($error == "") {
        $query = "SELECT * FROM faculty WHERE email= '$email'";
        if ($role == 'facuty') {
            $query = "SELECT * FROM faculty WHERE email= '$email'";
        } else if ($role == 'hod') {
            $query = "SELECT * FROM hod WHERE email= '$email'";
        } else if ($role == 'associatedean') {
            $query = "SELECT * FROM associatedean WHERE email= '$email'";
        } else if ($role == 'deanfaa') {
            $query = "SELECT * FROM dean WHERE email= '$email'";
        } else if ($role == 'director') {
            $query = "SELECT * FROM director WHERE Fid= '$email'";
        } else if ($role == 'admin') {
            $query = "SELECT * FROM admin_db WHERE email= '$email'";
        }
        $res = mysqli_query($mySql_db, $query);
        $row = mysqli_fetch_assoc($res);
        if (!isset($res)) {
            $error = "No such email matched in " + $role;
        }
        else if ($row["password"] == md5($password)) {
            $_SESSION["email"] = $email;
            $_SESSION["role"] = $role;
            if ($role == 'faculty') {
                echo 1;
            } else if ($role == 'admin') {
                echo 3;
            } else echo 2;
        } 
        else {
            $error = "Email - Password doesn't match!";
        }
    }
    if ($error != "") {
        echo $error;
    }
} else if ($_GET['action'] == "save_profile") {
    $collection = $database->user;
    $data = array('email' => $_SESSION["email"]);
    $count = $collection->findOne($data);
    if (!count($count)) {
        $user = array('email' => $_SESSION["email"], 'biography' => $_POST["biography"], 'research_area' => $_POST["research_area"], 'education' => $_POST["education"], 'experience' => $_POST["experience"], 'patents' => $_POST["patents"]);
        $collection->save($user);
    } else {
        $newdata = array('$set' => array('biography' => $_POST["biography"], 'research_area' => $_POST["research_area"], 'education' => $_POST["education"], 'experience' => $_POST["experience"], 'patents' => $_POST["patents"]));
        $collection->update(array("email" => $_SESSION["email"]), $newdata);
    }
    echo $_SESSION["email"];
} else if ($_GET['action'] == "unset") {
    unset($_SESSION['email']);
    unset($_SESSION['role']);
    echo 1;
} else if ($_GET["action"] == "CSE" || $_GET["action"] == "EE" || $_GET["action"] == "ME") {
    echo '<div id="primaryContent1">';
    if ($_GET["action"] == "CSE") {
        $query = "SELECT * FROM faculty WHERE department='cse'";
    }
    if ($_GET["action"] == "EE") {
        $query = "SELECT * FROM faculty WHERE department='ee'";
    }
    if ($_GET["action"] == "ME") {
        $query = "SELECT * FROM faculty WHERE department='me'";
    }
    $res = mysqli_query($mySql_db, $query);
    $count = 0;
    while ($row = mysqli_fetch_assoc($res)) {
        $count = 1;
        echo '<div class="fac_row">
        <div class="fac_img">
        <img src="../image/images.png">
        </div>';
        echo '<p> <a  href="../view_profile.php?action=' . $row['email'] . '">
                        <strong>' . $row['username'] . '</strong></a>
                    <br> Department: ' . $row['department'] . '
                    <br>Email id: ' . $row['email'] . '
                    <br>Position: ' . $row['role'] . '
                    <br>Date of Joining: ' . $row['startDate'] . '</p>
              </div>';
    }
    echo '</div>';
    if ($count == 0)
        echo '<p><strong>No faculty</strong><br></p>';
} else if ($_GET["action"] == "showhierarchy") {
    echo '<div id="primaryContent1"><div class="fac_row">';
    $query = "SELECT * FROM hierarchy ORDER BY rank";
    $res = mysqli_query($mySql_db, $query);
    $ttl = mysqli_num_rows($res);
    $show = "No hierarchy";
    while ($row = mysqli_fetch_assoc($res)) {
        echo ' ' . $row['From1'] . ' ------>';
        $show = $row['To1'];
    }
    echo $show;
    echo '</div></div>';
} else if ($_GET["action"] == "changehierarchy") {
    $query = "DELETE FROM hierarchy WHERE rank>0";
    $res = mysqli_query($mySql_db, $query);
    $Fa = $_POST['Fa'];
    $Ho = $_POST['Ho'];
    $Ad = $_POST['Ad'];
    $De = $_POST['De'];
    $Di = $_POST['Di'];
    if ($Fa == '0') {
        $query = "INSERT INTO hierarchy(rank,From1,To1) Values(1,'faculty','none')";
        $res = mysqli_query($mySql_db, $query);
    }
    if ($Ho == '0') {
        $query = "INSERT INTO hierarchy(rank,From1,To1) Values(2,'hod','none')";
        $res = mysqli_query($mySql_db, $query);
    }
    if ($Ad == '0') {
        $query = "INSERT INTO hierarchy(rank,From1,To1) Values(3,'associatedean','none')";
        $res = mysqli_query($mySql_db, $query);
    }
    if ($De == '0') {
        $query = "INSERT INTO hierarchy(rank,From1,To1) Values(4,'deanfaa','none')";
        $res = mysqli_query($mySql_db, $query);
    }
    if ($Di == '0') {
        $query = "INSERT INTO hierarchy(rank,From1,To1) Values(5,'director','none')";
        $res = mysqli_query($mySql_db, $query);
    }
    $query1 = "SELECT * FROM hierarchy ORDER BY rank";
    $query2 = "SELECT * FROM hierarchy ORDER BY rank";
    $res1 = mysqli_query($mySql_db, $query1);
    $res2 = mysqli_query($mySql_db, $query2);
    if (mysqli_num_rows($res1) > 0) {
        if (mysqli_num_rows($res1) == 1) {
            $query = "DELETE FROM hierarchy WHERE rank>0";
            $res = mysqli_query($mySql_db, $query);
        } else {
            $row1 = mysqli_fetch_assoc($res1);
            while ($row1 = mysqli_fetch_assoc($res1)) {
                $T = $row1['From1'];
                $row2 = mysqli_fetch_assoc($res2);
                $F = $row2['From1'];
                $query = "UPDATE hierarchy SET To1='$T' WHERE From1='$F'";
                $res = mysqli_query($mySql_db, $query);
            }
            $query = "DELETE FROM hierarchy WHERE To1='none'";
            $res = mysqli_query($mySql_db, $query);
        }
    }
} else if ($_GET['action'] == 'remainingleaves') {
    $mailid = $_POST['mail'];
    $query = "SELECT * FROM leaverecord WHERE Fid='$mailid'";
    $res = mysqli_query($mySql_db, $query);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $Avail = $row['leavesAvailable'];
        echo $Avail;
    } else {
        echo '20';
    }
} else if ($_GET['action'] == 'pastrecord') {
    $mailid = $_POST['mail'];
    $query = "SELECT * FROM pastrecord WHERE Fid='$mailid'";
    $res = mysqli_query($mySql_db, $query);
    if (mysqli_num_rows($res) == 0) {
        echo 'No leave applied untill....';
    } else {
        echo '<table class="table table-hover table-sm">
        <thead>
        <tr>
            <th scope="col">Leave id</th>
            <th scope="col">Leave type</th>
            <th scope="col">start date</th>
            <th scope="col">end date</th>
            <th scope="col">Approval Date</th>
            <th scope="col">View details</th>
            </tr>
        </thead>
        <tbody>';
        while ($row = mysqli_fetch_assoc($res)) {
            $Ltype = $row['leaveType'];
            $Lid = $row['Lid'];
            $sd = $row['startDate'];
            $ed = $row['endDate'];
            $apDate = $row['approvalDate'];
            if($apDate == '0000-00-00 00:00:00'){
                $apDate = 'rejected';
            }
            echo '<tr><td>' . $Lid . '
            </td><td> ' . $Ltype . '
            </td><td> ' . $sd . '
            </td><td> ' . $ed . '
            </td><td> ' . $apDate . '
            </td><td><a href="#" class="views" data-value="'.$Lid.'">view</a>
            </td></tr>';
        }
    }
} else if ($_GET['action'] == 'changepass') {
    $email = $_SESSION['email'];
    $password = $_POST['newpass'];
    $password2 = $_POST['confirmpass'];
    if (!$password) {
        $error = "Please enter password";
    }
    if (!$password2) {
        $error = "Please enter Confirm password";
    }
    if ($password != $password2) {
        $error = "Password and Confirm password are not matching";
    }
    if ($error == "") {
        $password = md5($password);
        $role = $_SESSION['role'];
        $qry = "UPDATE faculty SET password='$password' WHERE email = '$email'";
        $temp = 0;
        if ($role == 'hod') {
            $qry = "UPDATE hod SET password='$password' WHERE email = '$email'";
            $temp = 1;
        } else if ($role == 'associatedean') {
            $qry = "UPDATE associatedean SET password='$password' WHERE email = '$email'";
            $temp = 1;
        } else if ($role == 'deanfaa') {
            $qry = "UPDATE dean SET password='$password' WHERE email = '$email'";
            $temp = 1;
        } else if ($role == 'director') {
            $qry = "UPDATE director SET password='$password' WHERE email = '$email'";
            $temp = 1;
        } else if ($role == 'admin') {
            $qry = "UPDATE adimin_db SET password='$password' WHERE email = '$email'";
            $temp = 1;
        }
        mysqli_query($mySql_db, $qry);
        if ($temp == 0)
            echo 1;
        else
            echo 2;
    } else {
        echo $error;
    }
} else if ($_GET['action']='renew'){
    $q = "SELECT * FROM leaverecord";
    $r = mysqli_query($mySql_db,$q);
    while($w=mysqli_fetch_assoc($r)){
        $avail = $w['leavesAvailable'];
        $Fid = $w['Fid'];
        $var = 20;
        if($avail<0){
            $var = 20 + $avail;
        }
        $qry = "UPDATE leaverecord SET leavesAvailable = '$var'  WHERE Fid = '$Fid'";
        mysqli_query($mySql_db,$qry);
    }
    echo 1;
}

?>
