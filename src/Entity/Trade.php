<?php

namespace App\Entity;

use App\Repository\TradeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TradeRepository")
 */
class Trade
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $base_experience_left;

    /**
     * @ORM\Column(type="integer")
     */
    private $base_experience_right;

    /**
     * @ORM\Column(type="json")
     */
    private $pokemons_left = [];

    /**
     * @ORM\Column(type="json")
     */
    private $pokemons_right = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_fair;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_insert;
    
    const COUNT_PER_PAGE = 10;
    const ACCURACY = 30;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBaseExperienceLeft(): ?int
    {
        return $this->base_experience_left;
    }

    public function setBaseExperienceLeft(int $base_experience_left): self
    {
        $this->base_experience_left = $base_experience_left;

        return $this;
    }

    public function getBaseExperienceRight(): ?int
    {
        return $this->base_experience_right;
    }

    public function setBaseExperienceRight(int $base_experience_right): self
    {
        $this->base_experience_right = $base_experience_right;

        return $this;
    }

    public function getPokemonsLeft(): ?array
    {
        return $this->pokemons_left;
    }

    public function setPokemonsLeft(array $pokemons_left): self
    {
        $this->pokemons_left = $pokemons_left;

        return $this;
    }

    public function getPokemonsRight(): ?array
    {
        return $this->pokemons_right;
    }

    public function setPokemonsRight(array $pokemons_right): self
    {
        $this->pokemons_right = $pokemons_right;

        return $this;
    }

    public function getIsFair(): ?bool
    {
        return $this->is_fair;
    }

    public function setIsFair(bool $is_fair): self
    {
        $this->is_fair = $is_fair;

        return $this;
    }

    public function getDateInsert(): ?\DateTimeInterface
    {
        return $this->date_insert;
    }

    public function setDateInsert(\DateTimeInterface $date): self
    {
        $this->date_insert = $date;

        return $this;
    }
}
