<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="Demandeur", orphanRemoval=true)
     */
    private $tickets;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="technicien")
     */
    private $ticketsaresoudre;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="users")
     */
    private $userRoles;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->ticketsaresoudre = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setDemandeur($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getDemandeur() === $this) {
                $ticket->setDemandeur(null);
            }
        }

        return $this;
    }
    

    /**
     * @return Collection|Ticket[]
     */
    public function getTicketsaresoudre(): Collection
    {
        return $this->ticketsaresoudre;
    }

    public function addTicketsaresoudre(Ticket $ticketsaresoudre): self
    {
        if (!$this->ticketsaresoudre->contains($ticketsaresoudre)) {
            $this->ticketsaresoudre[] = $ticketsaresoudre;
            $ticketsaresoudre->setTechnicien($this);
        }

        return $this;
    }

    public function removeTicketsaresoudre(Ticket $ticketsaresoudre): self
    {
        if ($this->ticketsaresoudre->contains($ticketsaresoudre)) {
            $this->ticketsaresoudre->removeElement($ticketsaresoudre);
            // set the owning side to null (unless already changed)
            if ($ticketsaresoudre->getTechnicien() === $this) {
                $ticketsaresoudre->setTechnicien(null);
            }
        }

        return $this;
    }
    public function getRoles()
    {
        $roles=$this->userRoles->map(function ($role){
            return $role->getTitle();
        })->toArray();
        $roles[]='ROLE_USER';
        return $roles;
    }

    
    public function getSalt()
    {
        
    }
    public function getUsername()
    {
        return $this->email;
    }
    public function eraseCredentials()
    {
        
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserroles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserrole(Role $userrole): self
    {
        if (!$this->userRoles->contains($userrole)) {
            $this->userRoles[] = $userrole;
        }

        return $this;
    }

    public function removeUserrole(Role $userrole): self
    {
        if ($this->userRoles->contains($userrole)) {
            $this->userRoles->removeElement($userrole);
        }

        return $this;
    }
}
