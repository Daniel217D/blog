<?php

namespace DDaniel\Blog\Entities;

use DDaniel\Blog\Enums\SettingType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'settings')]
class Setting
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $key;

    #[ORM\Column(type: 'text')]
    private string $value;

    #[ORM\Column(type: 'string', length: 255, enumType: SettingType::class)]
    private SettingType $type;

    public function __construct()
    {
        $this->setId(0);
        $this->setKey('');
        $this->setValue('');
        $this->setType(SettingType::TEXT);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getType(): SettingType
    {
        return $this->type;
    }

    public function setType(SettingType|string $type): void
    {
        $this->type = is_string($type) ? SettingType::from( $type ) : $type;
    }
}