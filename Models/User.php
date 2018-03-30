<?php

class User
{
    private $userId;
    private $userType;
    private $firstName;
    private $lastName;
    private $username;
    private $password;
    private $position;
    private $emailId;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param mixed $userType
     */
    public function setUserType($userType): void
    {
        $this->userType = $userType;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position): void
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getEmailId()
    {
        return $this->emailId;
    }

    /**
     * @param mixed $emailId
     */
    public function setEmailId($emailId): void
    {
        $this->emailId = $emailId;
    }

    public function getFullName() : string {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function isStaff() : bool {
        return $this->getUserType() == 'supportstaff';
    }

    public static function convertFromUserXML($userXML) : User {
        $user = new User();

        $user->setUserId($userXML->attributes()->id.'');
        $user->setUserType($userXML->attributes()->type.'');
        $user->setFirstName($userXML->name->firstname);
        $user->setLastName($userXML->name->lastname);
        $user->setUsername($userXML->username);
        $user->setPassword($userXML->password);
        $user->setPosition($userXML->position);
        $user->setEmailId($userXML->emailid);

        return $user;
    }
}