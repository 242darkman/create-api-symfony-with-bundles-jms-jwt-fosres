<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     *
     * @Serializer\Groups({"detail"})
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     *
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     *
     * @Serializer\Groups({"detail"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=MachineOutil::class, mappedBy="user")
     */
    private $id_machine;


    public function __construct()
    {
        $this->id_machine = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }



    /**
     * @param $mail
     * @param $name
     * @return static
     */
    public static function create($mail, $name): self
    {
        $user = new self();
        $user->setMail($mail);
        $user->setName($name);

        return $user;
    }

    /**
     * @return Collection<int, MachineOutil>
     */
    public function getIdMachine(): Collection
    {
        return $this->id_machine;
    }

    public function addIdMachine(MachineOutil $idMachine): self
    {
        if (!$this->id_machine->contains($idMachine)) {
            $this->id_machine[] = $idMachine;
            $idMachine->setUser($this);
        }

        return $this;
    }

    public function removeIdMachine(MachineOutil $idMachine): self
    {
        if ($this->id_machine->removeElement($idMachine)) {
            // set the owning side to null (unless already changed)
            if ($idMachine->getUser() === $this) {
                $idMachine->setUser(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->mail;
    }

    public function eraseCredentials()
    {
    }
}
