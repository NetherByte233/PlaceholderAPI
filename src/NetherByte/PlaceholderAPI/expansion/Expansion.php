<?php

declare(strict_types=1);

namespace NetherByte\PlaceholderAPI\expansion;

use pocketmine\player\Player;

abstract class Expansion{
    public function __construct(protected object $plugin){}

    /**
     * Human-readable name of the expansion
     */
    abstract public function getName() : string;

    /**
     * Optional: prefix namespace for identifiers (e.g., "economy_", "netherperms_").
     * Return null to opt-out and be asked for all identifiers (legacy behavior).
     */
    public function getIdentifierPrefix() : ?string{ return null; }

    /** Optional metadata for /papi info */
    public function getAuthor() : ?string{ return null; }
    public function getVersion() : ?string{ return null; }
    public function getDescription() : ?string{ return null; }

    /**
     * Optional: return a positive number of seconds to cache values your expansion returns
     * for the same identifier/param/player tuple. 0 disables API-level caching.
     */
    public function getUpdateIntervalSeconds() : int{ return 0; }

    /**
     * Return a value for the given placeholder identifier or null if not handled.
     * Identifier is the text inside % %, e.g. "player_name" or "server_time_<format>"
     */
    abstract public function onRequest(string $identifier, ?Player $player) : ?string;

    /**
     * Optional param-aware variant. If an identifier of the form "base:param" is detected,
     * this method will be preferred if overridden. Default returns null to fall back to onRequest().
     */
    public function onRequestWithParams(string $base, ?string $param, ?Player $player) : ?string{ return null; }
}
