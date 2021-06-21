<?php

include("database.php");

//this gets called when the register form is being submitted
if (isset($_POST["registerSubmit"])) {

    
    try {
        if (isset($_POST["fullname"]) && isset($_POST["email"]) && isset($_POST["password"])) {

            //store submitted form values in variables
            $user_fullname = trim($_POST["fullname"]);
            $user_email = trim($_POST["email"]);
            $user_password = trim($_POST["password"]);
            $is_error = false;

            //validate users inputs
            if (empty($user_fullname)) {
                echo '<h4 class="text-danger text-center">Fullname must not be empty</h4>';
                $is_error = true;
            } elseif (strlen($user_fullname) < 3 || strlen($user_fullname) > 200) {
                echo '<h4 class="text-danger text-center">Fullname must be between 3 to 150 letters</h4>';
                $is_error = true;
            }
            if (empty($user_email)) {
                echo '<h4 class="text-danger text-center">Email must not be empty</h4>';
                $is_error = true;
            } elseif (strlen($user_email) > 200) {
                echo '<h4 class="text-danger text-center">That email is too long</h4>';
                $is_error = true;
            }
            if (empty($user_password)) {
                echo '<h4 class="text-danger text-center">Password must not be empty</h4>';
                $is_error = true;
            }

            if (strlen($user_password) < 8 || strlen($user_password) > 50) {
                echo '<h4 class="text-danger text-center">Password must be between 8 to 50 letters</h4>';
                $is_error = true;
            }

            if (!$is_error) {
                //check if another user has already signed up with that email
                $query = "SELECT * FROM userregister WHERE Email = ?";

                if ($stmt = $con->prepare($query)) {

                    $stmt->bind_param('s', $user_email);

                    if ($stmt->execute()) {
                        $stmt->store_result();

                        if ($stmt->num_rows > 0) {
                            //if the email exists then send an error message to the user
                            echo '<h4 class="text-danger text-center">Email already exists</h4>';
                        } else {

                            //encrypt the users password before saving to the database
                            $_hashedPassword = password_hash($user_password, PASSWORD_DEFAULT);
                            $_query = 'INSERT INTO userregister (FullName,Email,UserPassword) values (?,?,?)';
                            $statement = $con->prepare($_query);
                            $statement->bind_param('sss', $user_fullname, $user_email, $_hashedPassword);

                            if ($statement->execute()) {
                                echo '<h4 class="text-success text-center">Account created successfully <a href="login.php">Click here to login</a></h4>';
                            }
                        }
                    } else {

                        echo '<h4 class="text-danger text-center">Sorry, Error Signing Up. Please Try again</h4>';
                    }
                } else {
                    echo '<h4 class="text-danger text-center">Oops!! There was an Internal Server Error</h4>';
                }
            }
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="./css/main.css" type="text/css">
</head>

<body>
    <div class="container">
        <div class="row mt-5">

            <div class="col-md-12 text-center">
                <h1 class="display-3 text-info">Welcome to Ansu's Medicine Tracker</h1>
            </div>
          
            <div class="col-md-7 col-12  offset-md-2 p-5">
                
                <form action="" method="POST" autocomplete="off">
                    <h4 class="text-uppercase">Create an account today!</h4>
                    <div class="form-group mb-2">
                        <label for="">Enter your fullname</label>
                        <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Fullname">
                    </div>

                    <div class="form-group mb-2">
                        <label for="">Enter your Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email">
                    </div>


                    <div class="form-group mb-2">
                        <label for="">Enter your password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password">
                    </div>

                    <input type="submit" value="Create New Account" class="form-control btn btn-success my-3" name="registerSubmit">
                </form>
               <p class="text-muted"><a href="login.php">I already have an account</a></p>
            </div>
        </div>
    </div>

    <?php set_footer() ?>