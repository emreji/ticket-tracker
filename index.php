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

<?php include 'header.php';?>
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
                    <input type="password" name="password" class="form-control" value="<?php if(isset($_POST['password'])){echo $_POST['password'];} ?>" placeholder="Your password">
                </div>
                <input type="submit" class="btn btn-primary" name="login" value="Log In">
            </div>
        </div>
    </fieldset>
</form>