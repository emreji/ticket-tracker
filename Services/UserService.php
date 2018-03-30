<?php
require_once './Models/User.php';

class UserService {

    public function login($username, $password) : ?User {
        $xml = simplexml_load_file('xml/users.xml');

        for ($i = 0; $i < count($xml); $i++) {
            if($xml->user[$i]->username == $username) {
                if($xml->user[$i]->password == $password) {

                    $user = User::convertFromUserXML($xml->user[$i]);
                    return $user;

                }
            }
        }
        return null;
    }

    public function getUserByUserId($userId) : User {
        $users = simplexml_load_file('xml/users.xml');
        $user = $users->xpath('/users/user[@id='.$userId.']')[0];

        return User::convertFromUserXML($user);
    }
}