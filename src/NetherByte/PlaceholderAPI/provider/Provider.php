<?php

declare(strict_types=1);

namespace NetherByte\PlaceholderAPI\provider;

use NetherByte\PlaceholderAPI\expansion\Expansion;

interface Provider{
    /** Human-readable provider name (usually the plugin name) */
    public function getName() : string;

    /**
     * Return a list of expansion identifiers this provider can supply.
     * Example identifiers: "vault", "luckperms", "myplugin_stats"
     *
     * @return string[]
     */
    public function listExpansions() : array;

    /**
     * Return an Expansion instance for the given identifier or null if unknown.
     */
    public function provide(string $identifier) : ?Expansion;
}
