<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
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
    private $ticketname;

    /**
     * @ORM\Column(type="text")
     */
    private $ticketdescription;

    /**
     * @ORM\Column(type="date")
     */
    private $ticketcreatedate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $ticketclosedate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ticketstatut;

    /**
     * @ORM\ManyToOne(targetEntity=Priority::class, inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Priority;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Demandeur;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ticketsaresoudre")
     */
    private $technicien;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicketname(): ?string
    {
        return $this->ticketname;
    }

    public function setTicketname(string $ticketname): self
    {
        $this->ticketname = $ticketname;

        return $this;
    }

    public function getTicketdescription(): ?string
    {
        return $this->ticketdescription;
    }

    public function setTicketdescription(string $ticketdescription): self
    {
        $this->ticketdescription = $ticketdescription;

        return $this;
    }

    public function getTicketcreatedate(): ?\DateTimeInterface
    {
        return $this->ticketcreatedate;
    }

    public function setTicketcreatedate(\DateTimeInterface $ticketcreatedate): self
    {
        $this->ticketcreatedate = $ticketcreatedate;

        return $this;
    }

    public function getTicketclosedate(): ?\DateTimeInterface
    {
        return $this->ticketclosedate;
    }

    public function setTicketclosedate(?\DateTimeInterface $ticketclosedate): self
    {
        $this->ticketclosedate = $ticketclosedate;

        return $this;
    }

    public function getTicketstatut(): ?string
    {
        return $this->ticketstatut;
    }

    public function setTicketstatut(string $ticketstatut): self
    {
        $this->ticketstatut = $ticketstatut;

        return $this;
    }

    public function getPriority(): ?Priority
    {
        return $this->Priority;
    }

    public function setPriority(?Priority $Priority): self
    {
        $this->Priority = $Priority;

        return $this;
    }

    public function getDemandeur(): ?User
    {
        return $this->Demandeur;
    }

    public function setDemandeur(?User $Demandeur): self
    {
        $this->Demandeur = $Demandeur;

        return $this;
    }

    public function getTechnicien(): ?User
    {
        return $this->technicien;
    }

    public function setTechnicien(?User $technicien): self
    {
        $this->technicien = $technicien;

        return $this;
    }
}
