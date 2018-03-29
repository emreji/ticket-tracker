<?php
require_once 'datasource.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!DataSource::isAvailable()) {
    echo 'Data Source not available';
    return;
}

session_start();
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

    if(isset($_POST['back'])) {
        if(isStaff($_SESSION['id'])){
            header('Location:staffHome.php');
        } else {
            header('Location:clientHome.php');
        }
    }

    if(isset($_POST['updateStatus'])) {
        $ticket->status = $_POST['issueStatus'];
        $tickets->saveXML('xml/supportsystem.xml');
    }
}

function isStaff($userId) : bool {
    $users = simplexml_load_file('xml/users.xml');
    $user = $users->xpath('/users/user[@id='.$userId.']')[0];
    $userType = $user->attributes()->type;
    return ($userType == 'supportstaff');
}

function getName($userId) : string {
    $users = simplexml_load_file('xml/users.xml');
    $user = $users->xpath('/users/user[@id='.$userId.']')[0];
    return $user->name->firstname . ' ' . $user->name->lastname;
}

function isCurrentStatusEquals($ticket, $status) : string {
    return (strtolower($ticket->status) == strtolower($status)) ? 'selected' : '';
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
        <link rel="stylesheet" href="styles/style.css">
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
        <form action="" method="post">
            <dl class="row">
                <dt class="col-sm-3">Ticket Number</dt>
                <dd class="col-sm-9"><?php echo $ticket->ticketnumber ?></dd>

                <dt class="col-sm-3">Issue Date</dt>
                <dd class="col-sm-9"><?php echo $ticket->issuedate ?></dd>

                <dt class="col-sm-3">Status</dt>
                <?php if(isStaff($_SESSION['id'])) { ?>
                    <dd class="col-sm-9">
                        <select class="col-sm-3" id="issueStatus" name="issueStatus">
                            <option value="New" <?php echo isCurrentStatusEquals($ticket, "New") ?>>New</option>
                            <option value="On-going" <?php echo isCurrentStatusEquals($ticket, "On-going") ?> >On-going</option>
                            <option value="Resolved" <?php echo isCurrentStatusEquals($ticket, "Resolved") ?>>Resolved</option>
                        </select>
                        <input class="col-sm-3 btn btn-primary mb-1" type="submit" name="updateStatus" value="Update status">
                    </dd>
                <?php } else { ?>
                    <dd class="col-sm-9"><?php echo $ticket->status ?></dd>
                <?php } ?>
                <dt class="col-sm-3 text-truncate">Category</dt>
                <dd class="col-sm-9"><?php echo $ticket->attributes() ?></dd>
            </dl>
            <h4>Messages</h4>
            <dl class="row">
                <?php foreach ($messages as $msg): ?>
                    <?php $userId = $msg->attributes(); ?>
                    <?php if (isStaff($userId)) {?>
                        <dt class="col-sm-2"><?php echo getName($userId)?> (Staff) : </dt>
                        <dd class="col-sm-9"><?php echo $msg->message?></dd>
                    <?php } else { ?>
                        <dt class="col-sm-2"><?php echo getName($userId)?> : </dt>
                        <dd class="col-sm-9"><?php echo $msg->message?></dd>
                    <?php } ?>
                <?php endforeach; ?>
            </dl>
            <div class="form-group">
                <textarea class="col-sm- form-control" id="message" name="message" rows="3"></textarea>
            </div>
            <input type="submit" name="addMessage" class="btn btn-primary mb-2" value="Add Message"/>
            <input type="submit" name="back" class="btn btn-primary mb-2" value="Back"/>
        </form>
    </body>
</html>
