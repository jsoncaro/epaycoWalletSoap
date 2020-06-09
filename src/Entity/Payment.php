<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment
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
    private $token;

    /**
     * @ORM\Column(type="boolean")
     */
    private $confirmed;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=Wallet::class, inversedBy="payments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $wallet;

    /**
     * @ORM\OneToOne(targetEntity=Session::class, mappedBy="payment", cascade={"persist", "remove"})
     */
    private $session;

    /**
     * @ORM\OneToOne(targetEntity=Session::class, mappedBy="payment", cascade={"persist", "remove"})
     */
    private $sessionUuid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentId(): ?int
    {
        return $this->paymentId;
    }

    public function setPaymentId(int $paymentId): self
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getConfirmed(): ?bool
    {
        return $this->confirmed;
    }

    public function setConfirmed(bool $confirmed): self
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(Session $session): self
    {
        $this->session = $session;

        // set the owning side of the relation if necessary
        if ($session->getPayment() !== $this) {
            $session->setPayment($this);
        }

        return $this;
    }

    public function getSessionUuid(): ?Session
    {
        return $this->sessionUuid;
    }

    public function setSessionUuid(Session $sessionUuid): self
    {
        $this->sessionUuid = $sessionUuid;

        // set the owning side of the relation if necessary
        if ($sessionUuid->getPayment() !== $this) {
            $sessionUuid->setPayment($this);
        }

        return $this;
    }
}
