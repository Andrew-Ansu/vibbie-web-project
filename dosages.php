<?php
include("database.php");
include("functions.php");
session_start();
if (!isset($_SESSION['loggedIn'])) {
    header("location: login.php");
}else{

$query = "SELECT * FROM medicine WHERE user_ID = " . $_SESSION['user_id'] . "";
$result = mysqli_query($con, $query);

$user_id = intval($_SESSION["user_id"]);

if (isset($_POST["btn_save_dosage"])) {

    $medicine_id = $_POST["medicine_id"];
    $date_taken = $_POST["date_taken"];
    $time_taken = $_POST["time_taken"];

    $query = "INSERT INTO tbl_dosages(medicine_id,user_id, date_taken, time_taken)
        VALUES(?,?,?,?)";

    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param("iiss", $medicine_id, $user_id, $date_taken, $time_taken);

        if ($stmt->execute()) {
            echo '<h4 class="text-success text-center">Successfully Saved Dosage</h4>';
            header("location: dosages.php");
        } else {
            echo '<h4 class="text-danger text-center">Error Saving Dosage</h4>';
        }
    }else{
        echo '<h4 class="text-danger text-center">Internal Server Error</h4>';
    }
}

if(isset($_GET["delete_dosage"]) && $_GET["delete_dosage"] == true && isset($_GET["dosage_id"]))
{
  $dosage_id =intval($_GET["dosage_id"]);
        
  $query = "DELETE FROM tbl_dosages WHERE dosage_id = ?";
  if($stmt = $con->prepare($query)){
    $stmt->bind_param("i",$dosage_id);
    if($stmt->execute()){
      echo '<h5 class="display-4 text-center">Dosage Deleted Successfuully</h5>';
      header("location: dosages.php");
    }else{
      echo '<h5 class="display-4">Error Deleting Record. Try Again</h5>';
    }
  }

}




}

?>
<?php include_page_header("New Dosage") ?>

<div class="contianer">
    <div class="row">
        <div class="col-md-4 p-5">
            <form action="" method="post">
                <div class="form-group">
                    <label for="">Select Medicine</label>
                    <select name="medicine_id" id="medicine_id" class="form-select" required>
                        <?php
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . $row['ID'] . "'>" . $row['medicine_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Enter Date Taken</label>
                    <input type="date" name="date_taken" id="date_taken" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Enter Time Taken</label>
                    <input type="time" name="time_taken" id="time_taken" required class="form-control">
                </div>

                <input type="submit" name="btn_save_dosage" value="Save New Dosage" class="btn btn-success my-2 btn-block">
            </form>
        </div>

        <div class="col-md-7">
            <main>
            <h3>List of all Dosages</h3>
                <ul class="list-group">
                <?php
                    $query = 'SELECT dosage_id,medicine_name,date_taken,time_taken FROM tbl_dosages inner join medicine on tbl_dosages.medicine_id = medicine.ID WHERE tbl_dosages.user_id = ' . $user_id . '';
                    $result = mysqli_query($con, $query);

                    if ($result) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<li class="list-group-item d-flex justify-content-between align-items-center">
                                <small>'.$row["medicine_name"].'</small>
                                <small>'.$row["date_taken"].'</small>
                                <small>'.$row["time_taken"].'</small>
                                <a href="?edit_dosage=true&dosage_id='.$row["dosage_id"].'" class="btn btn-info">Edit</a>
                                <a href="?delete_dosage=true&dosage_id='.$row["dosage_id"].'" class="btn btn-danger">Delete</a>
                            </li>';
                            echo "<br>";
                        }
                    } else {
                        echo '<h4 class="text-mute text-center">No Dosage Record Found</h4>';
                    }
                    ?>
                    
                  
                </ul>
               
            </main>
        </div>
    </div>
</div>
<?php set_footer()?>