<?php
require_once 'datasource.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!DataSource::isAvailable()) {
    echo 'Data Source not available';
    exit();
}

session_start();

$userId = $_SESSION['id'];
$users = simplexml_load_file('xml/users.xml');
$user = $users->xpath('/users/user[@id='.$userId.']')[0];
$tickets = simplexml_load_file('xml/supportsystem.xml');
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
              integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="styles/style.css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
                integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <title>Home</title>
    </head>
    <body>
        <h1>Welcome <?php echo $user->name->firstname . ' ' . $user->name->lastname ?></h1>
        <h2>Ticket Information</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ticket Number</th>
                    <th scope="col">Category</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0;?>
                <?php foreach ($tickets->children() as $ticket) : ?>
                    <?php $i++?>
                    <tr>
                        <th scope="row"><?php echo $i ?></th>
                        <td><a href="ticketInfo.php?id=<?php echo $ticket->ticketnumber; ?>"><?php echo $ticket->ticketnumber ?></a></td>
                        <td><?php echo $ticket->attributes() ?></td>
                        <td><?php echo $ticket->status ?></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <a class="btn btn-primary mb-2" name="logout" href="index.php">Logout</a>
    </body>
</html>
