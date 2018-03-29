<?php
require_once 'Message.php';

class Ticket
{
    private $ticketNumber;
    private $issueDate;
    private $status;
    private $clientId;
    private $category;
    private $messages = array();

    /**
     * @return mixed
     */
    public function getTicketNumber()
    {
        return $this->ticketNumber;
    }

    /**
     * @param mixed $ticketNumber
     */
    public function setTicketNumber($ticketNumber): void
    {
        $this->ticketNumber = $ticketNumber;
    }

    /**
     * @return mixed
     */
    public function getIssueDate()
    {
        return $this->issueDate;
    }

    /**
     * @param mixed $issueDate
     */
    public function setIssueDate($issueDate): void
    {
        $this->issueDate = $issueDate;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    public static function convertFromTicketXML($ticketXML) : Ticket {
        $ticket = new Ticket();

        $ticket->setTicketNumber($ticketXML->ticketnumber);
        $ticket->setIssueDate($ticketXML->issuedate);
        $ticket->setStatus($ticketXML->status);
        $ticket->setClientId($ticketXML->clientid);
        $ticket->setCategory($ticketXML->attributes()->category);

        $messages = array();
        foreach ($ticketXML->supportmessages->supportmessage as $msgXML) {
            array_push($messages, Message::convertFromMessageXML($msgXML));
        }
        $ticket->setMessages($messages);

        return $ticket;
    }


}