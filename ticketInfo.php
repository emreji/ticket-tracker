<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if(file_exists('xml/supportsystem.xml')) {

    //supportsystem/supportticket/ticketnumber/text()
    if(isset($_GET['id'])) {
        $ticketId = $_GET['id'];

        $tickets = simplexml_load_file('xml/supportsystem.xml');
        $ticket = $tickets->xpath("/supportsystem/supportticket/ticketnumber[text()='$ticketId']/parent::*")[0];
        $messages = $ticket->supportmessage;


        if (isset($_POST['addMessage'])) {

            $supportmessage = $ticket->addChild('supportmessage');
            $supportmessage->addAttribute('userid', $_SESSION['id']);
            $message = $supportmessage->addChild('message', $_POST['message']);
            $message = $supportmessage->addChild('time', (new DateTime())->format('Y-m-d H:i:s'));

            $tickets->saveXML('xml/supportsystem.xml');
        }
    }
} else {
    echo "No file found!";
}

function getUserRole($userId){
    if(file_exists('xml/users.xml')) {
        $users = simplexml_load_file('xml/users.xml');
        $user = $users->xpath('/users/user[@id='.$userId.']')[0];
        return $user->attributes()->type;
    } else {
        echo "No file found!";
    }
}
function getUsername($userId){
    if(file_exists('xml/users.xml')) {
        $users = simplexml_load_file('xml/users.xml');
        $user = $users->xpath('/users/user[@id='.$userId.']')[0];
        return $user->username;
    } else {
        echo "No file found!";
    }
}
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
        <link rel="stylesheet" href="style.css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
                integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <title>Ticket Information</title>
    </head>
    <body>
        <h1>Ticket Information</h1>
        <dl class="row">
            <dt class="col-sm-3">Ticket Number</dt>
            <dd class="col-sm-9"><?php echo $ticket->ticketnumber ?></dd>

            <dt class="col-sm-3">Issue Date</dt>
            <dd class="col-sm-9"><?php echo $ticket->issuedate ?></dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9"><?php echo $ticket->status ?></dd>

            <dt class="col-sm-3 text-truncate">Category</dt>
            <dd class="col-sm-9"><?php echo $ticket->attributes() ?></dd>
        </dl>
        <h4>Messages</h4>
        <dl class="row">
            <?php foreach ($messages as $msg): ?>
                <?php if (getUserRole($msg->attributes()) == 'client') {?>
                    <dt class="col-sm-2"><?php echo getUsername($msg->attributes())?> : </dt>
                    <dd class="col-sm-9"><?php echo $msg->message?></dd>
                <?php }?>
                <?php if (getUserRole($msg->attributes()) == 'supportstaff') {?>
                    <dt class="col-sm-2"><?php echo getUsername($msg->attributes())?>(Staff) : </dt>
                    <dd class="col-sm-9"><?php echo $msg->message?></dd>
                <?php }?>
            <?php endforeach; ?>
        </dl>
        <form action="" method="post">
            <div class="form-group">
                <textarea class="form-control" id="message" name="message" rows="3"></textarea>
            </div>
            <input type="submit" name="addMessage" class="btn btn-primary mb-2" value="Add Message"/>
        </form>
    </body>
</html>
