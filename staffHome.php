<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './datasource.php';
require_once './Services/TicketService.php';
require_once './Services/UserService.php';

if (!DataSource::isAvailable()) {
    echo 'Data Source not available';
    exit();
}

session_start();
if (!isset($_SESSION['id']) || isset($_POST['logout'])) {
    session_destroy();
    header('Location:index.php');
}

$ticketService = new TicketService();
$tickets = $ticketService->getAllTickets();

$userId = $_SESSION['id'];
$userService = new UserService();
$user = $userService->getUserByUserId($userId);
?>

<?php include 'header.php'?>
<h1>Welcome <?php echo $user->getFirstName() . ' ' . $user->getLastName() ?></h1>
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
        <?php foreach ($tickets as $ticket) : ?>
            <?php $i++?>
            <tr>
                <th scope="row"><?php echo $i ?></th>
                <td>
                    <a href="ticketInfo.php?id=<?php echo $ticket->getTicketNumber(); ?>">
                        <?php echo $ticket->getTicketNumber(); ?>
                    </a>
                </td>
                <td><?php echo $ticket->getCategory(); ?></td>
                <td><?php echo $ticket->getStatus(); ?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
<form method="post">
    <input type="submit" class="btn btn-primary mb-2" name="logout" value="Logout">
</form>
<?php include 'footer.php';?>