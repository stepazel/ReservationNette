<?php


namespace App\Model;

use Nette;
use Nette\Security\IIdentity;

class MyAuthenticator implements Nette\Security\IAuthenticator {

    /** @var Nette\Database\Context */
    private $database;

    private $passwords;

    public function __construct(Nette\Database\Context $database, Nette\Security\Passwords $passwords) {
        $this->database = $database;
        $this->passwords = $passwords;
    }

    public function authenticate(array $credentials): Nette\Security\IIdentity {
        [$username, $password] = $credentials;

        $row = $this->database->table('users')
            ->where('username', $username)->fetch();

        if (!$row) {
            throw new Nette\Security\AuthenticationException('User not found.');
        }

        if (!$this->passwords->verify($password, $row->password)) {
            throw new Nette\Security\AuthenticationException('Invalid password.');
        }

        return new Nette\Security\Identity($row->id, $row->role, ['username' => $row->username]);
    }
}