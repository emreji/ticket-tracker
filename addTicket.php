<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './datasource.php';
require_once './Services/TicketService.php';

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

<?php include "header.php"; ?>
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
                <option value="New" selected>New</option>
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
    <a href="clientHome.php" class="btn btn-primary">Cancel</a>
</form>
<?php include "footer.php"; ?>
