<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './datasource.php';
require_once './Services/UserService.php';

if (!DataSource::isAvailable()) {
    echo 'Data Source not available';
    exit();
}

session_start();
$errorFlag = false;

if (isset($_POST['login'])) {

    $usernameInput = $_POST['username'];
    $passwordInput = $_POST['password'];

    $userService = new UserService();
    $loggedInUser = $userService->login($usernameInput, $passwordInput);

    if ($loggedInUser == null) {
        $loginError = "Incorrect username/password!";
        $errorFlag = true;
    } else {
        $_SESSION['id'] = $loggedInUser->getUserId();
        $_SESSION['type'] = $loggedInUser->getUserType();

        if ($loggedInUser->isStaff()) {
            header("Location:staffHome.php");
        } else {
            header("Location:clientHome.php");
        }
    }

}


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>User Login</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
              integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="styles/style.css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
                integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </head>
    <body>
        <form class="login-form" method="post">
            <fieldset>
                <div class="jumbotron jumbotron-fluid">
                    <h1 class="display-4">Support System</h1>
                    <hr class="my-4">
                    <div class="container">
                        <label name="errors"><?php if($errorFlag == true){ echo $loginError; } ?></label>
                        <div class="form-group">
                            <label for="username">Username : </label>
                            <input type="text" name="username" class="form-control" value="<?php if(isset($_POST['username'])){echo $_POST['username'];} ?>" placeholder="Your username">
                        </div>
                        <div class="form-group">
                            <label for="password">Password : </label>
                            <input type="text" name="password" class="form-control" value="<?php if(isset($_POST['password'])){echo $_POST['password'];} ?>" placeholder="Your password">
                        </div>
                        <input type="submit" class="btn btn-primary" name="login" value="Log In">
                    </div>
                </div>
            </fieldset>
        </form>
    </body>
</html>