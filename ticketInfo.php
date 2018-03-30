<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './datasource.php';
require_once './Services/TicketService.php';
require_once './Services/UserService.php';

if (!DataSource::isAvailable()) {
    echo 'Data Source not available';
    return;
}

session_start();
$userService = new UserService();
$loggedInUser = $userService->getUserByUserId($_SESSION['id']);


if (!isset($_SESSION['id']) || isset($_POST['logout'])) {
    session_destroy();
    header('Location:index.php');
}

if(isset($_GET['id'])) {
    $ticketId = $_GET['id'];

    $ticketService = new TicketService();
    $ticket = $ticketService->getTicketById($ticketId);
    $messages = $ticket->getMessages();

    if (isset($_POST['addMessage'])) {
        $message = new Message();
        $message->setSenderId($_SESSION['id']);
        $message->setDateSent((new DateTime())->format('Y-m-d H:i:s'));
        $message->setMessageBody($_POST['message']);
        $ticketService->addMessage($ticketId, $message);

        // fetch updated ticket (with the message that was just added)
        $ticket = $ticketService->getTicketById($ticketId);
        $messages = $ticket->getMessages();
    }

    if(isset($_POST['back'])) {
        if($loggedInUser->isStaff()){
            header('Location:staffHome.php');
        } else {
            header('Location:clientHome.php');
        }
    }

    if(isset($_POST['updateStatus'])) {
        $ticketService->updateStatus($ticketId, $_POST['issueStatus']);
        $ticket = $ticketService->getTicketById($ticketId);
    }
}

function isCurrentStatusEquals(Ticket $ticket, string  $status) : string {
    return (strtolower($ticket->getStatus()) == strtolower($status)) ? 'selected' : '';
}
?>
<?php include "header.php";?>
<h1>Ticket Information</h1>
<form action="" method="post">
    <dl class="row">
        <dt class="col-sm-3">Ticket Number</dt>
        <dd class="col-sm-9"><?php echo $ticket->getTicketNumber() ?></dd>

        <dt class="col-sm-3">Issue Date</dt>
        <dd class="col-sm-9"><?php echo $ticket->getIssueDate() ?></dd>

        <dt class="col-sm-3">Status</dt>
        <?php if($loggedInUser->isStaff()) { ?>
            <dd class="col-sm-9">
                <select class="col-sm-3" id="issueStatus" name="issueStatus">
                    <option value="New" <?php echo isCurrentStatusEquals($ticket, "New") ?>>New</option>
                    <option value="On-going" <?php echo isCurrentStatusEquals($ticket, "On-going") ?> >On-going</option>
                    <option value="Resolved" <?php echo isCurrentStatusEquals($ticket, "Resolved") ?>>Resolved</option>
                </select>
                <input class="col-sm-3 btn btn-primary mb-1" type="submit" name="updateStatus" value="Update status">
            </dd>
        <?php } else { ?>
            <dd class="col-sm-9"><?php echo $ticket->getStatus() ?></dd>
        <?php } ?>
        <dt class="col-sm-3 text-truncate">Category</dt>
        <dd class="col-sm-9"><?php echo $ticket->getCategory() ?></dd>
    </dl>
    <h4>Messages</h4>
    <dl class="row">
        <?php foreach ($messages as $msg): ?>
            <?php $messageSender = $userService->getUserByUserId($msg->getSenderId()); ?>
            <?php if ($messageSender->isStaff()) {?>
                <dt class="col-sm-2"><?php echo $messageSender->getFullName(); ?> (Staff) : </dt>
                <dd class="col-sm-9"><?php echo $msg->getMessageBody(); ?></dd>
            <?php } else { ?>
                <dt class="col-sm-2"><?php echo $messageSender->getFullName(); ?> : </dt>
                <dd class="col-sm-9"><?php echo $msg->getMessageBody(); ?></dd>
            <?php } ?>
        <?php endforeach; ?>
    </dl>
    <div class="form-group">
        <textarea class="col-sm- form-control" id="message" name="message" rows="3"></textarea>
    </div>
    <input type="submit" name="addMessage" class="btn btn-primary mb-2" value="Add Message"/>
    <input type="submit" name="back" class="btn btn-primary mb-2" value="Back"/>
</form>
<?php include 'footer.php';?>