<?php

declare(strict_types=1);

namespace NetherByte\PlaceholderAPI\expansion;

use pocketmine\plugin\Plugin;

final class ExpansionManager{
    /** @var Expansion[] */
    private array $expansions = [];

    public function __construct(private Plugin $owner){}

    public function register(Expansion $expansion) : void{
        $this->expansions[spl_object_id($expansion)] = $expansion;
    }

    public function clear() : void{
        $this->expansions = [];
    }

    /**
     * @return Expansion[]
     */
    public function getAll() : array{
        return $this->expansions;
    }
}
