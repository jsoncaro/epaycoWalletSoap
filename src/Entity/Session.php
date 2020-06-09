<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\ORM\Mapping as ORM;
//use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Payment::class, inversedBy="session", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $payment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sessionUuid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getSessionUuid(): ?string
    {
        return $this->sessionUuid;
    }

    public function setSessionUuid(string $sessionUuid): self
    {
        $this->sessionUuid = $sessionUuid;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(): self
    {
        
        $minutes_to_add = 30;
        $time = new \DateTime();
        $time->setTimezone(new \DateTimeZone('America/Bogota'));

        $time->add(new \DateInterval('PT' . $minutes_to_add . 'M'));

        $stamp = $time->format('Y-m-d H:i:s');
        $expiresAt = new \DateTime($stamp);
        $this->expiresAt = $expiresAt;

        return $this;
    }
}
