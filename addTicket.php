<?php
require_once 'datasource.php';
require_once './Services/TicketService.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!DataSource::isAvailable()) {
    echo 'Data Source not available';
    exit();
}

session_start();
if (!isset($_SESSION['id']) || isset($_POST['logout'])) {
    session_destroy();
    header('Location:index.php');
}
if(isset($_POST['add'])) {
    $ticket = new Ticket();
    $ticket->setTicketNumber($_POST['ticketNumber']);
    $ticket->setIssueDate($_POST['issueDate']);
    $ticket->setStatus($_POST['issueStatus']);
    $ticket->setCategory($_POST['issueCategory']);
    $ticket->setClientId($_SESSION['id']);
    $ticket->setMessages([$_POST['message']]);

    $ticketService = new TicketService();
    if ($ticketService->addTicket($ticket)) {
        header('Location: clientHome.php');
    } else {
        echo 'Ticket not added!';
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
        <link rel="stylesheet" href="styles/style.css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
                integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <title>Add Ticket</title>
    </head>
    <body>
        <h2>Add Ticket</h2>
        <form method="post">
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="ticketNumber">Ticket Number</label>
                    <input type="text" class="form-control" name="ticketNumber">
                </div>
                <div class="form-group col-md-4">
                    <label for="issueDate">Issue Date</label>
                    <input type="date" class="form-control" name="issueDate" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="issueStatus">Status</label>
                    <select class="form-control" name="issueStatus" required>
                        <option>Please select</option>
                        <option value="New">New</option>
                        <option value="On-going">On-going</option>
                        <option value="Resolved">Resolved</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="issueCategory">Issue Category</label>
                    <select class="form-control" name="issueCategory" required>
                        <option>Please select</option>
                        <option value="Hardware">Hardware</option>
                        <option value="Software">Software</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control" id="message" name="message" rows="3" required placeholder="Please describe the issue"></textarea>
            </div>
            <input type="submit" name="add" class="btn btn-primary" value="Add Ticket"/>
        </form>
    </body>
</html>
