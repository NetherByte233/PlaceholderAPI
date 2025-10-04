<?php

declare(strict_types=1);

namespace NetherByte\PlaceholderAPI\provider;

use NetherByte\PlaceholderAPI\expansion\Expansion;
use NetherByte\PlaceholderAPI\Main;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Filesystem;

final class ProviderManager{
    /** @var Provider[] */
    private array $providers = [];

    /** @var array<string, true> Installed expansion identifiers */
    private array $installed = [];

    public function __construct(private Main $owner){
        $this->loadInstalled();
    }

    public function register(Provider $provider) : void{
        $this->providers[spl_object_id($provider)] = $provider;
    }

    /** @return Provider[] */
    public function getAll() : array{ return $this->providers; }

    /**
     * Get a map of providerName => string[] expansion identifiers offered
     * @return array<string, string[]>
     */
    public function getOfferings() : array{
        $out = [];
        foreach($this->providers as $p){
            $out[$p->getName()] = $p->listExpansions();
        }
        return $out;
    }

    /** List installed expansions identifiers */
    public function getInstalled() : array{ return array_keys($this->installed); }

    /** Persist installation list */
    private function saveInstalled() : void{
        $dir = $this->owner->getDataFolder();
        @mkdir($dir, 0777, true);
        file_put_contents($dir . 'installed.json', json_encode(array_keys($this->installed), JSON_PRETTY_PRINT));
    }

    private function loadInstalled() : void{
        $file = $this->owner->getDataFolder() . 'installed.json';
        if(is_file($file)){
            $data = json_decode((string) @file_get_contents($file), true);
            if(is_array($data)){
                foreach($data as $id){ $this->installed[(string)$id] = true; }
            }
        }
    }

    /**
     * Attempt to install an expansion by identifier from any registered provider.
     * Returns the Expansion instance on success.
     */
    public function install(string $identifier) : ?Expansion{
        foreach($this->providers as $p){
            $exp = $p->provide($identifier);
            if($exp !== null){
                $this->owner->getExpansionManager()->register($exp);
                $this->installed[$identifier] = true;
                $this->saveInstalled();
                return $exp;
            }
        }
        return null;
    }

    /**
     * Reinstall all previously installed expansions from current providers.
     * Skips ones that cannot be provided.
     */
    public function reinstallAll() : int{
        $ok = 0;
        foreach(array_keys($this->installed) as $id){
            $exp = $this->install($id);
            if($exp !== null){ $ok++; }
        }
        return $ok;
    }
}
