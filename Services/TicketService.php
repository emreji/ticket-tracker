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
}