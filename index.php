<?php
include("database.php");
include("functions.php");
session_start();

if (!isset($_SESSION['loggedIn'])) {
  header("location: login.php");
}

if(isset($_GET["delete"]) && $_GET["delete"] == true && isset($_GET["medicine_id"]))
{
  $medicine_id =intval($_GET["medicine_id"]);
        
  $query = "DELETE FROM medicine WHERE ID = ?";
  if($stmt = $con->prepare($query)){
    $stmt->bind_param("i",$medicine_id);
    if($stmt->execute()){
      echo '<h5 class="display-4">Record Deleted Successfully</h5>';
      header("location: index.php");
    }else{
      echo '<h5 class="display-4">Error Deleting Record. Try Again</h5>';
    }
  }

}


?>

<?php include_page_header("Dashboard") ?>

<div class="container">
  <div class="row mt-5 px-5">
    <div class="col-md-12">

      <div class="p-2 d-flex justify-content-between mb-2">
      <a href="medicine.php" class="btn btn-info mb-3">Enter a new Medicine</a>
      <a href="dosages.php" class="btn btn-warning mb-3">Enter new Dosage</a>
      </div>
      <div class="table-responsive">

        <table class="table table-striped table-dark">
          <thead>
            <tr>
             
              <th>Medicine Name</th>
              <th>Dosage </th>
              <th>Unit</th>
              <th>Milligrams</th>
              <th>Unit</th>
              <th>Frequency</th>
              <th>Unit</th>
              <th colspan="2">More Action</th>
            </tr>
          </thead>
          <tbody>
          <?php
                $query = "SELECT * FROM medicine WHERE user_ID = ".$_SESSION['user_id']."";
                $result = mysqli_query($con, $query);

                if($result){
                    $count = 1;
                    while($rows = mysqli_fetch_array($result)){
                      echo '
                      <tr>
                        <td>' . $rows['medicine_name'] . '</td>
                        <td>' . $rows['dosage_quantity'] . '</td>
                        <td>' . $rows['dosage_unit'] . '</td>
                        <td>' . $rows['milligram_quantity'] . '</td>
                        <td>' . $rows['milligram_unit'] . '</td>
                        <td>' . $rows['frequency_quantity'] . '</td>
                        <td>' . $rows['frequency_unit'] . '</td>
                        <td><a class="btn btn-primary btn-block" href="edit_drug.php?id='.$rows['ID'].'">EDIT</a></td>
                        <td><a class="btn btn-danger btn-block" href="index.php?delete=true&medicine_id='.$rows['ID'].'">DELETE</a></td>
                    </tr>
                ';
                        $count += 1;
                    }
                }else{
                    echo "<h1>MEDICINES NOT FOUND</h1>";
                }
            ?>
          </tbody>
        </table>
      </div>


    </div>

  </div>

</div>
<?php set_footer()?>