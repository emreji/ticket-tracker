<?php

require_once './Models/Ticket.php';

class TicketService {

    public function getUserTickets($userId) : array {

        $tickets = simplexml_load_file('./xml/supportsystem.xml');
        $clientTickets = $tickets->xpath('/supportsystem/supportticket/clientid[text()='.$userId.']/parent::*');
        return $this->convertXMLTickets($clientTickets);
    }

    public function getAllTickets() : array {

        $tickets = simplexml_load_file('./xml/supportsystem.xml');
        return $this->convertXMLTickets($tickets);
    }

    private function convertXMLTickets($xmlTickets) : array {

        $tickets = array();
        foreach ($xmlTickets as $xmlTicket) {
            $ticket = Ticket::convertFromTicketXML($xmlTicket);
            array_push($tickets, $ticket);
        }
        return $tickets;
    }

    public function addTicket(Ticket $ticketAdd) : bool {

        $tickets = simplexml_load_file('xml/supportsystem.xml');
        $ticket = $tickets->addChild('supportticket');
        $ticket->addChild('ticketnumber', $ticketAdd->getTicketNumber());
        $ticket->addChild('issuedate', $ticketAdd->getIssueDate());
        $ticket->addChild('status', $ticketAdd->getStatus());
        $ticket->addAttribute('category', $ticketAdd->getCategory());
        $ticket->addChild('clientid', $ticketAdd->getClientId());
        $supportmessages = $ticket->addChild('supportmessages');
        $supportmessage = $supportmessages->addChild('supportmessage');
        $supportmessage->addAttribute('userid', $ticketAdd->getClientId());
        $supportmessage->addChild('message', $ticketAdd->getMessages()[0]);
        $supportmessage->addChild('time', (new DateTime())->format('Y-m-d H:i:s'));

        return $tickets->saveXML('xml/supportsystem.xml');
        }

}