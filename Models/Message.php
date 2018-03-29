<?php

class Message
{
    private $senderId;
    private $messageBody;
    private $dateSent;

    /**
     * @return mixed
     */
    public function getSenderId()
    {
        return $this->senderId;
    }

    /**
     * @param mixed $senderId
     */
    public function setSenderId($senderId): void
    {
        $this->senderId = $senderId;
    }

    /**
     * @return mixed
     */
    public function getMessageBody()
    {
        return $this->messageBody;
    }

    /**
     * @param mixed $messageBody
     */
    public function setMessageBody($messageBody): void
    {
        $this->messageBody = $messageBody;
    }

    /**
     * @return mixed
     */
    public function getDateSent()
    {
        return $this->dateSent;
    }

    /**
     * @param mixed $dateSent
     */
    public function setDateSent($dateSent): void
    {
        $this->dateSent = $dateSent;
    }

    public static function convertFromMessageXML($msgXML) : Message {
        $message = new Message();

        $message->setMessageBody($msgXML->message);
        $message->setSenderId($msgXML->attributes()->userid);
        $message->setDateSent($msgXML->time);

        return $message;
    }

}