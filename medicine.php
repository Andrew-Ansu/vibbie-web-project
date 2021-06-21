<?php
session_start();
include("database.php");
include("functions.php");

if (!isset($_SESSION['loggedIn'])) {
    header("location: login.php");
} else {
    $user_id = $_SESSION["user_id"];
    $medicineName = "";
    $dosageQty = "";
    $dosageUnit = "";
    $milligramQty ="";
    $milligramUnit = "";
    $frequencyQty = "";
    $frequencyUnit = "";

    if (isset($_POST["btn_save_medicine"])) {
        try {
            //store form variables in variables
            $medicineName = trim($_POST["medicine_name"]);
            $dosageQty = intval(trim($_POST["dosage_quantity"]));
            $dosageUnit = trim($_POST["dosage_unit"]);
            $milligramQty = intval(trim($_POST["milligram_quantity"]));
            $milligramUnit = trim($_POST["milligram_unit"]);
            $frequencyQty = intval(trim($_POST["frequency_quantity"]));
            $frequencyUnit = trim($_POST["frequency_unit"]);



            $query = "INSERT INTO medicine (medicine_name, dosage_quantity, dosage_unit, milligram_quantity, milligram_unit, frequency_quantity, frequency_unit, user_ID)
                VALUES (?,?,?,?,?,?,?,?);";

            if ($statement = $con->prepare($query)) {
                if ($statement->bind_param("sisisisi", $medicineName, $dosageQty, $dosageUnit, $milligramQty, $milligramUnit, $frequencyQty, $frequencyUnit, $user_id)) {

                    if ($statement->execute()) {
                        echo '<h4 class="text-success text-center">Medicine Saved Successfully</h4>';
                    } else {
                        echo '<h4 class="text-danger text-center">There was an error saving medicne</h4>';
                    }
                } else {
                    echo '<h4 class="text-danger text-center">Internal Server Error</h4>';
                }
            } else {
                echo '<h4 class="text-danger text-center">Internal Server Error</h4>';
            }


        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}


?>

<?php include_page_header("New Medicine"); ?>
<div class="container">
    <div class="row ">
        <div class="col-md-6">
            <h5 class="display-5">Save New Medicine</h5>
            <a href="index.php" class="btn btn-info pill-right mb-4">View All Medicines</a>
            <form action="" method="post">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Name Of Medicine</label>
                            <input type="text" name="medicine_name" id="medicine_name" placeholder="Medicine Name" required class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Dosage Quantity</label>
                            <input type="number" name="dosage_quantity" id="dosage_quantity" required class="form-control" min="1" max="300" value="1">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                    <div class="form-group">
                    <label for="">Dosage Unit</label>
                    <select name="dosage_unit" id="dosage_unit" class="form-select" required>
                        <option value="Tablet" selected>Tab</option>
                        <option value="Bottle">Bottle</option>
                        <option value="Syringe">Syringe/Injection</option>
                    </select>
                </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                    <label for="">Milligrams</label>
                    <input type="text" name="milligram_quantity" id="milligram" placeholder="Milligram" required class="form-control">
                </div>
                    </div>
                </div>
                   
                   <div class="row">
                       <div class="col-md-6">
                       <div class="form-group">
                    <label for="">Unit(g/mg)</label>
                    <select name="milligram_unit" id="unit" class="form-select" required>
                        <option value="Grams" selected>Grams</option>
                        <option value="MilliGrams">MilliGrams</option>
                    </select>
                </div>
                       </div>
                       <div class="col-md-6">
                       <div class="form-group">
                    <label for="">Frequency Quantity</label>
                    <input type="number" name="frequency_quantity" id="frequency_quantity" placeholder="Frequency Quantity" required class="form-control" min="1" max="300" value="1">
                </div>
                       </div>
                   </div>
        

                <div class="form-group">
                    <label for="">Frequency Unit</label>
                    <select name="frequency_unit" id="frequency_unit" class="form-select">
                        <option value="Daily" selected>Daily</option>
                        <option value="Weekly">Weekly</option>
                    </select>
                </div>
                <input type="submit" name="btn_save_medicine" value="Add Medicne" class="form-control btn btn-info btn-block my-3">
            </form>
            
        </div>
    </div>
</div>

<?php set_footer()?>