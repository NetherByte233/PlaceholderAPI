<?php

declare(strict_types=1);

namespace NetherByte\PlaceholderAPI;

use NetherByte\PlaceholderAPI\expansion\Expansion;
use NetherByte\PlaceholderAPI\provider\Provider;
use NetherByte\PlaceholderAPI\util\TextParser;
use pocketmine\player\Player;

final class PlaceholderAPI{
    /**
     * In-memory cache: [expansionClass][base][paramKey][playerKey] => [value, expiresAt]
     */
    private static array $cache = [];

    /**
     * Parse all placeholders in the given text for the provided player context.
     */
    public static function parse(string $text, ?Player $player = null) : string{
        $manager = Main::getInstance()->getExpansionManager();
        return TextParser::replace($text, function(string $raw) use ($manager, $player) : ?string{
            $base = $raw; $param = null;
            // Support identifier:param syntax; if multiple ':', split at first
            $pos = strpos($raw, ':');
            if($pos !== false){
                $base = substr($raw, 0, $pos);
                $param = substr($raw, $pos + 1);
                if($param === ''){ $param = null; }
            }

            // First pass: ask expansions with matching prefix
            $value = self::resolveWithExpansions($manager->getAll(), $base, $param, $player, true);
            if($value !== null){
                return $value;
            }
            // Fallback: ask all expansions (legacy behavior)
            return self::resolveWithExpansions($manager->getAll(), $base, $param, $player, false);
        });
    }

    /**
     * Get the value of a single placeholder identifier (without surrounding %).
     */
    public static function get(string $rawIdentifier, ?Player $player = null) : ?string{
        $manager = Main::getInstance()->getExpansionManager();
        $base = $rawIdentifier; $param = null;
        $pos = strpos($rawIdentifier, ':');
        if($pos !== false){
            $base = substr($rawIdentifier, 0, $pos);
            $param = substr($rawIdentifier, $pos + 1);
            if($param === ''){ $param = null; }
        }
        $value = self::resolveWithExpansions($manager->getAll(), $base, $param, $player, true);
        if($value !== null){ return $value; }
        return self::resolveWithExpansions($manager->getAll(), $base, $param, $player, false);
    }

    /**
     * Direct expansion registration is deprecated and disabled.
     * Third-party plugins must implement a Provider and be installed via /papi download <id>.
     */
    public static function registerExpansion(Expansion $expansion) : void{
        Main::getInstance()->getLogger()->warning(
            "registerExpansion() is disabled. Please implement Provider and use /papi download <id> to install expansions."
        );
        // no-op
    }

    /**
     * Register a provider that can supply expansions on demand.
     */
    public static function registerProvider(Provider $provider) : void{
        Main::getInstance()->getProviderManager()->register($provider);
    }

    /**
     * Internal: resolve using expansions with optional prefix enforcement and simple caching.
     * @param Expansion[] $expansions
     */
    private static function resolveWithExpansions(array $expansions, string $base, ?string $param, ?Player $player, bool $respectPrefix) : ?string{
        foreach($expansions as $exp){
            if($respectPrefix){
                $prefix = $exp->getIdentifierPrefix();
                if($prefix !== null && !str_starts_with($base, $prefix)){
                    // skip if prefix is declared and doesn't match
                    continue;
                }
            }

            $cacheTtl = max(0, (int) $exp->getUpdateIntervalSeconds());
            $playerKey = $player?->getName() ?? '@server';
            $paramKey = $param ?? '@none';
            if($cacheTtl > 0){
                $class = get_class($exp);
                $bucket = self::$cache[$class][$base][$paramKey][$playerKey] ?? null;
                if($bucket !== null && $bucket[1] >= time()){
                    return $bucket[0];
                }
            }

            $value = $exp->onRequestWithParams($base, $param, $player);
            if($value === null){
                // Combine base and param for legacy fallback if expansion expects single identifier
                $identifier = $param !== null ? ($base . '_' . $param) : $base;
                $value = $exp->onRequest($identifier, $player);
            }

            if($value !== null){
                if($cacheTtl > 0){
                    $class = get_class($exp);
                    self::$cache[$class][$base][$paramKey][$playerKey] = [$value, time() + $cacheTtl];
                }
                return $value;
            }
        }
        return null;
    }
}
