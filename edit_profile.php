<?php
include("tool/header.php");
include("tool/functions.php");
if (!isset($_SESSION['email'])) {
  header("Location: check.php");
} else {
    $mailid = $_SESSION['email'];
    $query = "SELECT * FROM faculty WHERE email= '$mailid'";
    $res = mysqli_query($mySql_db, $query);
    if (mysqli_num_rows($res) == 0) {
      header("Location: ../check.php");
    }
  }

  $email = $_SESSION['email'];
  $val = "SELECT * FROM faculty WHERE email= '$email'";
  $res = mysqli_query($mySql_db, $val);
  $row = mysqli_fetch_assoc($res);
  $fid = $row["username"];
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
      // $fid = "dkm";
      $filter = ['facultyID' => $fid];
      $options = [
      ];

      $query = new MongoDB\Driver\Query($filter,$options);
?>

<script>
$(document).ready(function() {
  $("save").click(function() {
    <?php
  $bulk = new MongoDB\Driver\BulkWrite;
  $bulk->update(
      ['facultyID' => $fid],
      ['$set' => ['biography' => $_POST['biography']]]
      //['multi' => false, 'upsert' => false]
  );
  $result = $manager->executeBulkWrite('Faculty.Biography', $bulk);

  ?>
  <?php
  $bulk = new MongoDB\Driver\BulkWrite;
  $bulk->update(
    ['facultyID' => $fid],
    ['$set' => ['research' => $_POST['research_area']]]
    //['multi' => false, 'upsert' => false]
  );
  $result = $manager->executeBulkWrite('Faculty.Research', $bulk);
?>
<?php
  $bulk = new MongoDB\Driver\BulkWrite;
  $bulk->update(
    ['facultyID' => $fid],
    ['$set' => ['education' => $_POST['education']]]
    //['multi' => false, 'upsert' => false]
  );
  $result = $manager->executeBulkWrite('Faculty.Education', $bulk);
?>
<?php
  $bulk = new MongoDB\Driver\BulkWrite;
  $bulk->update(
    ['facultyID' => $fid],
    ['$set' => ['experience' => $_POST['experience']]]
    //['multi' => false, 'upsert' => false]
  );
  $result = $manager->executeBulkWrite('Faculty.Work Experience', $bulk);
?>
<?php
  $bulk = new MongoDB\Driver\BulkWrite;
  $bulk->update(
    ['facultyID' => $fid],
    ['$set' => ['publication' => $_POST['patents']]]
    //['multi' => false, 'upsert' => false]
  );
  $result = $manager->executeBulkWrite('Faculty.Publications', $bulk);
?>
  });
});
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<link rel="stylesheet" href="css/facultypage.css">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="Faculty/faculty.php">Faculty Portal</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <!-- <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="MyProfile" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          My Profile</a>
        <div class="dropdown-menu" aria-labelledby="MyProfile">
          <a class="dropdown-item" id="ViewProfile" href="#">View Profile</a>
          <a class="dropdown-item" id="EditProfile" href="#">Edit Profile</a>
        </div>
      </li> -->
      <li class="nav-item">
        <a class="nav-link" id="ApplyLeave" href="../apply_leave.php">Apply Leave</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="LeaveRecord" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Leave Record
        </a>
        <div class="dropdown-menu" aria-labelledby="LeaveRecord">
          <a class="dropdown-item" href="#" id="remainingleaves">Remaining leaves</a>
          <a class="dropdown-item" id="leaveStatus" href="#">Current leave status</a>
          <a class="dropdown-item" id="pastrecord" href="#">Past record</a>
        </div>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <button class="btn btn-outline-success my-2 my-sm-0" type="button" id="changepass" style="margin-right:5px;">ChangePassword</button>
      <button class="btn btn-outline-success my-2 my-sm-0" type="button" id="logout">Logout</button>
    </form>
  </div>
</nav>

<div class="container">
<div id="primaryContent1">
  <div class="fac_row">
    <div class="fac_img">
      <img style="border:1px #e5e5e5 solid;" src="image/images.png">
    </div>
    <p>
      <br><strong id="mail"><?php echo $_SESSION["email"]; ?></strong>
    </p>
</div>
</div>
</div>
<form actions="/actions.php?action=save_profile" method="post">
  <div class="container edit" padding-top="20px">
    <div class="form-group shadow-textarea">
      <label for="biography" padding-bottom="0">Biography</label>
      <textarea class="form-control z-depth-1" value="<?php 
        $cursor = $manager->executeQuery("Faculty.Biography",$query);
        foreach($cursor as $document){
          print_r($document->biography);
        } ?>" name="biography" rows="3" placeholder="Write something here..."><?php 
        $cursor = $manager->executeQuery("Faculty.Biography",$query);
        foreach($cursor as $document){
          print_r($document->biography);
        } ?></textarea>
    </div>
  </div>
  <div class="container edit" padding-top="50px">
    <div class="form-group shadow-textarea">
      <label for="research_area" padding-bottom="0">Areas of Research</label>
      <textarea class="form-control z-depth-1" value="" name="research_area" rows="3" placeholder="Write something here..."><?php 
        $cursor = $manager->executeQuery("Faculty.Research",$query);
        foreach($cursor as $document){
          print_r($document->research);
        } ?></textarea>
    </div>
  </div>
  <div class="container edit" padding-top="50px">
    <div class="form-group shadow-textarea">
      <label for="education" padding-bottom="0">Education</label>
      <textarea class="form-control z-depth-1" value="" name="education" rows="3" placeholder="Write something here..."><?php 
        $cursor = $manager->executeQuery("Faculty.Education",$query);
        foreach($cursor as $document){
          print_r($document->education);
        } ?></textarea>
    </div>
  </div>
  <div class="container edit" padding-top="50px">
    <div class="form-group shadow-textarea">
      <label for="experience" padding-bottom="0">Work Experience</label>
      <textarea class="form-control z-depth-1" value="" name="experience" rows="3" placeholder="Write something here..."><?php 
        $cursor = $manager->executeQuery("Faculty.Work Experience",$query);
        foreach($cursor as $document){
          print_r($document->experience);
        } ?></textarea>
    </div>
  </div>
  <div class="container edit" padding-top="50px">
    <div class="form-group shadow-textarea">
      <label for="patents" padding-bottom="0">Selected Publications/Patents</label>
      <textarea class="form-control z-depth-1" value="" name="patents" rows="3" placeholder="Write something here..."><?php 
        $cursor = $manager->executeQuery("Faculty.Publications",$query);
        foreach($cursor as $document){
          print_r($document->publication);
        } ?></textarea>
    </div>
  </div>
  <div class="container">
    <form class="form-inline my-1">
      <input class="btn btn-outline-white btn-sm my-0 pull-right" type="submit" name="save" value="Save">
    </form>
  </div>

  
</form>

<script type="text/javascript">
  $("#changepass").click(function() {
    window.location.href = "changepassword.php";
  })
  var val = "<?php echo $_SESSION['email']; ?>";
  $("#ViewProfile").click(function() {
    window.location.href = "view_profile.php?action=" + val;
  })
  $("#EditProfile").click(function() {
    window.location.href = "edit_profile.php";
  })

  $(document).ready(function() {
    var val = "<?php echo $mailid; ?>"
    $("#logout").click(function() {
      $.ajax({
        type: "POST",
        url: "actions.php?action=unset",
        data: "random",
        success: function(result) {
          if (result == 1) {
            window.location.href = "../index.php";
          } else {
            alert("contact kml");
          }
        }
      });
    });
    $("#leaveStatus").click(function() {
      $.ajax({
        type: "POST",
        url: "leaveStatus.php",
        data: "random",
        success: function(result) {
          $("#change").html(result);
        }
      });
    });
    $("#remainingleaves").click(function() {
      $.ajax({
        type: "POST",
        url: "actions.php?action=remainingleaves",
        data: "mail=" + val,
        success: function(result) {
          alert(result);
        }
      });
    });
    $("#pastrecord").click(function() {
      $.ajax({
        type: "POST",
        url: "actions.php?action=pastrecord",
        data: "mail=" + val,
        success: function(result) {
          $("#change").html(result);
          $(".views").click(function() {
            $.ajax({
              type: "POST",
              url: "findInfo.php",
              data: "leaveId=" + $(this).data("value"),
              success: function(result) {
                $('#change').html(result);
              }
            })

          });
        }
      });
    });
  });
</script>
</body>

</html>