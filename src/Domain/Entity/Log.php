<?php

declare(strict_types=1);

namespace LoggerCrudBundle\Domain\Entity;

use AcademCity\CoreBundle\Domain\Entity\Traits\HasModifier;
use AcademCity\CoreBundle\Domain\Entity\Traits\HasTimestamps;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Monolog\Level;

#[ORM\Entity]
#[ORM\Table(name: 'logger_crud_bundle_logs')]
#[ORM\HasLifecycleCallbacks]
class Log
{
    use HasTimestamps;
    use HasModifier;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[ORM\Column(type: Types::TEXT)]
    private string $message;
    #[ORM\Column(type: Types::JSON)]
    private array $context;
    #[ORM\Column(enumType: Level::class)]
    private Level $level;

    #[ORM\Column(type: Types::JSON)]
    private array $extra;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    public function getLevel(): Level
    {
        return $this->level;
    }

    public function setLevel(Level $level): static
    {
        $this->level = $level;
        return $this;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    public function getExtra(): array
    {
        return $this->extra;
    }

    public function setExtra(array $extra): self
    {
        $this->extra = $extra;
        return $this;
    }


}
