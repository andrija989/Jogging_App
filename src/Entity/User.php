<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int $id
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string $email
     *
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @var string $roles
     *
     * @ORM\Column(type="string")
     */
    private $roles;

    /**
     * @var string The hashed password
     *
     * @ORM\Column(type="string")
     */
    private $password;


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return [$this->roles];
    }

    /**
     * @param $roles string
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @see UserInterface
     */
    /**
     * @return string
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @param string $password
    */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function createRecord(): Record
    {
        return new Record(
            $this
        );
    }
}
