<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Const\MembershipState;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV7;

#[Entity]
class Node
{
    /**
     * @param Collection<int, VirtualNode> $virtualNodes
     */
    public function __construct(
        #[Column(type: Types::STRING, length: 255)] private string $host,
        #[Column(type: Types::INTEGER)] private int $networkPort,
        #[Column(type: Types::SMALLINT, enumType: MembershipState::class)] private MembershipState $membershipState,
        #[Column(type: Types::DATETIME_IMMUTABLE)] private \DateTimeImmutable $joinedAt,
        #[Column(type: Types::SMALLINT)] private int $weight,
        #[Column(type: Types::BOOLEAN)] private bool $selfEntry,
        #[Column(type: Types::BOOLEAN)] private bool $seed,
        #[Column(type: Types::STRING, length: 10, unique: true)] private string $label,
        #[Column(type: Types::DATETIME_IMMUTABLE)] private \DateTimeImmutable $updatedAt,
        #[OneToMany(targetEntity: VirtualNode::class, mappedBy: 'node')] private Collection $virtualNodes = new ArrayCollection(),
        #[Id] #[Column(type: UuidType::NAME, unique: true)] private UuidV7 $id = new UuidV7()
    ) {
    }

    public function getId(): ?UuidV7
    {
        return $this->id;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return positive-int
     */
    public function getNetworkPort(): int
    {
        return $this->networkPort;
    }

    public function getMembershipState(): MembershipState
    {
        return $this->membershipState;
    }

    public function setMembershipState(MembershipState $membershipState): static
    {
        $this->membershipState = $membershipState;

        return $this;
    }

    public function getJoinedAt(): \DateTimeImmutable
    {
        return $this->joinedAt;
    }

    /**
     * @return positive-int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    public function isSelfEntry(): bool
    {
        return $this->selfEntry;
    }

    public function isSeed(): bool
    {
        return $this->seed;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return Collection<int, VirtualNode>
     */
    public function getVirtualNodes(): Collection
    {
        return $this->virtualNodes;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param Collection<int, VirtualNode> $virtualNodes
     */
    public function setVirtualNodes(Collection $virtualNodes): void
    {
        $this->virtualNodes = $virtualNodes;
    }

    public function update(): self
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function setNetworkPort(int $networkPort): self
    {
        $this->networkPort = $networkPort;

        return $this;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function setSeed(bool $seed): self
    {
        $this->seed = $seed;

        return $this;
    }
}
