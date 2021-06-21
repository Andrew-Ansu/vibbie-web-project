<?php

include("database.php");

//gets called when the login form is submitted
if (isset($_POST["btn_login"])) {
    if (isset($_POST['email']) && isset($_POST['password'])) {

        $user_email = trim($_POST['email']);
        $user_password = trim($_POST['password']);

        $is_error = false;

        if (empty($user_email)) {
            echo '<h4 class="text-danger text-center">Email must no be empty</h4>';
            $is_error = true;
        
        }elseif (!filter_var($user_email, FILTER_DEFAULT)) {
            echo '<h4 class="text-danger text-center">That email is invalid</h4>';
            $is_error = true;
        } 
        if (empty($user_password)) {
            echo '<h4 class="text-danger text-center">Password must not be empty</h4>';
            $is_error = true;
        }elseif (strlen($user_password) < 8 || strlen($user_password) > 50) {
            echo '<h4 class="text-danger text-center">Password must be at least 8 characters</h4>';
            $is_error = true;
        }


        //if there are no errors then login the user into his account
        if (!$is_error) {
            
            //check if there is an account with the email the user provided
            $sql = "SELECT UserID,FullName,Email,UserPassword FROM userregister WHERE Email = ?";
            if ($stmt = $con->prepare($sql)) {
               
                $stmt->bind_param("s", $user_email);
                if ($stmt->execute()) {
                    $stmt->store_result();

                    // if there is an account then continue
                    if ($stmt->num_rows == 1) {

                        // Bind result variables
                        $stmt->bind_result($id, $fullname, $email, $password);
                        if ($stmt->fetch()) {

                            //check if the password provided by the user matches the one in the database 
                            if (password_verify($user_password, $password)) {
                                //if the password is correct then start a new user session
                                session_start();
                                // Store data in session variables
                                $_SESSION["loggedIn"] = true;
                                $_SESSION["user_id"] = $id;
                                $_SESSION["fullname"] = $fullname;
                                $_SESSION["email"] = $email;

                                // Take the user to the index/home page of the application
                                header("location: index.php");
                            } else {
                                echo '<h4 class="text-danger text-center">Incorrect Email or Password</h4>';
                            }
                        }
                    }else{
                        echo '<h4 class="text-danger text-center">Email or Password is incorrect</h4>';
                    }
                }
            }
        }
    } else {
        header("location: login.php");
    }
}

// //handles register form
// if (isset($_POST["registerSubmit"])) {

//     //use try catch to handle all errors
//     try {
//         if (isset($_POST["fullname"]) && isset($_POST["email"]) && isset($_POST["password"])) {

//             //variables for storing form values
//             $_fullname = trim($_POST["fullname"]);
//             $_email = trim($_POST["email"]);
//             $_password = trim($_POST["password"]);
//             $is_error = false;

//             //validate users inputs
//             if (empty($_fullname)) {
//                 echo '<h4 class="text-danger text-center">Fullname is required</h4>';
//                 $is_error = true;
//             } elseif (strlen($_fullname) < 3 || strlen($_fullname) > 200) {
//                 echo '<h4 class="text-danger text-center">Fullname must be between 3 to 200 character</h4>';
//                 $is_error = true;
//             }
//             if (empty($_email)) {
//                 echo '<h4 class="text-danger text-center">Email is required</h4>';
//                 $is_error = true;
//             } elseif (strlen($_email) > 200) {
//                 echo '<h4 class="text-danger text-center">Email is too long</h4>';
//                 $is_error = true;
//             }
//             if (empty($_password)) {
//                 echo '<h4 class="text-danger text-center">Password is required</h4>';
//                 $is_error = true;
//             }

//             if (strlen($_password) < 8 || strlen($_password) > 50) {
//                 echo '<h4 class="text-danger text-center">Password must be between 8 to 50 characters</h4>';
//                 $is_error = true;
//             }

//             if (!$is_error) {
//                 //check if the email  exists
//                 $query = "SELECT * FROM userregister WHERE Email = ?";

//                 if ($stmt = $con->prepare($query)) {

//                     $stmt->bind_param('s', $_email);

//                     if ($stmt->execute()) {
//                         $stmt->store_result();

//                         if ($stmt->num_rows > 0) {
//                             //email already exists
//                             echo '<h4 class="text-danger text-center">That email is already registered, please try another one</h4>';
//                         } else {

//                             //hash the users password
//                             $_hashedPassword = password_hash($_password, PASSWORD_DEFAULT);
//                             $_query = 'INSERT INTO userregister (FullName,Email,UserPassword) values (?,?,?)';
//                             $statement = $con->prepare($_query);
//                             $statement->bind_param('sss', $_fullname, $_email, $_hashedPassword);

//                             if ($statement->execute()) {

//                                 echo '<h4 class="text-success text-center">Registeration Successful</h4>';
//                             }
//                         }
//                     } else {

//                         echo '<h4 class="text-danger text-center">Oops!! Something went wrong</h4>';
//                     }
//                 } else {
//                     echo '<h4 class="text-danger text-center">Oops!! Something went wrong</h4>';
//                 }
//             }
//         }
//     } catch (Exception $ex) {
//         echo $ex->getMessage();
//     }
// }



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
                <h1 class="display-3">Web Medicine Project</h1>
            </div>
            <div class="col-md-8 col-12 offset-md-2 p-md-5 p-sm-3 my-5">
                <form action="login.php" method="post" autocomplete="off">
                    <h4 class="text-uppercase">Login</h4>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                    </div>

                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password">
                    </div>

                    <input type="submit" value="Login" class="form-control btn btn-info my-3" name="btn_login">
                </form>

               <p class="text-muted"><a href="">I have forgotten my password</a></p>
               <p class="text-muted"><a href="register.php">Register a new account</a></p>
            </div>

        </div>
    </div>

    <?php set_footer() ?>