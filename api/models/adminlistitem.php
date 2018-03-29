<?php
namespace TeamAlpha\Web;

class AdminListItem
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $active;
    public $verified;
    public $blocked;

    public function __construct(array $data)
    {
        if ($data !== null) {
            $this->id = (int) $data['id'] ?? 0;
            $this->firstname = $data['firstname'] ?? null;
            $this->lastname = $data['lastname'] ?? null;
            $this->email = $data['email'] ?? null;
            $this->active = (int) $data['active'] ?? 0;
            $this->verified = (int) $data['verified'] ?? 0;
            $this->blocked = (int) $data['blocked'] ?? 0;
        }
    }
}
