<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[ORM\Index(name: 'email_idx', columns: ['email'])]
#[ORM\HasLifecycleCallbacks]
class User extends Base implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $active = false;

    #[ORM\Column]
    private ?bool $blocked = false;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column]
    private ?string $timeZone = User::DEFAULT_TIMEZONE;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

	/**
	 * @return 
	 */
	public function getEmail(): ?string {
		return $this->email;
	}
	
	/**
	 * @param  $email 
	 * @return self
	 */
	public function setEmail(?string $email): self {
		$this->email = $email;
		return $this;
	}

	/**
	 * @return 
	 */
	public function getActive(): ?bool {
		return $this->active;
	}
	
	/**
	 * @param  $active 
	 * @return self
	 */
	public function setActive(?bool $active): self {
		$this->active = $active;
		return $this;
	}

	/**
	 * @return 
	 */
	public function getBlocked(): ?bool {
		return $this->blocked;
	}
	
	/**
	 * @param  $blocked 
	 * @return self
	 */
	public function setBlocked(?bool $blocked): self {
		$this->blocked = $blocked;
		return $this;
	}

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setDateTimes() {
        $this->updatedAt = new \DateTime("now", new \DateTimeZone($this->timeZone));
        if($this->createdAt == null) {
            $this->createdAt = new \DateTime("now", new \DateTimeZone($this->timeZone));
        }
    }
}
