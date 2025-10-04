<?php

declare(strict_types=1);

namespace NetherByte\PlaceholderAPI\expansion\builtin;

use NetherByte\PlaceholderAPI\expansion\Expansion;
use NetherByte\PlaceholderAPI\Main;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

final class BuiltinExpansion extends Expansion{
    public function getName() : string{ return 'Builtin'; }
    public function getAuthor() : ?string{ return 'NetherByte'; }
    public function getVersion() : ?string{ return '1.0'; }
    public function getDescription() : ?string{ return 'Core player/server placeholders (name, tps, uptime, time, etc.)'; }

    /**
     * Cache fast-changing metrics for a short period to avoid recomputation spam.
     * 1 second is safe for TPS/online/uptime.
     */
    public function getUpdateIntervalSeconds() : int{ return 1; }

    public function onRequest(string $identifier, ?Player $player) : ?string{
        // Player-related
        if(str_starts_with($identifier, 'player_')){
            if($player === null || !$player->isOnline()){
                // For player placeholders, if no player provided, return empty
                return '';
            }
            return $this->handlePlayerPlaceholder(substr($identifier, 7), $player);
        }

        // Server-related
        if(str_starts_with($identifier, 'server_')){
            return $this->handleServerPlaceholder(substr($identifier, 7));
        }

        // Special cross lookups e.g. player_ping_<playername>
        if(preg_match('/^player_ping_([^%]+)$/', $identifier, $m) === 1){
            $target = Server::getInstance()->getPlayerExact($m[1]);
            return $target !== null ? (string) $target->getNetworkSession()->getPing() : '0';
        }

        return null;
    }

    /**
     * Param-aware support. Examples:
     * - server_time:<php_date_format>
     * - server_online:<world>
     */
    public function onRequestWithParams(string $base, ?string $param, ?Player $player) : ?string{
        if(str_starts_with($base, 'server_')){
            $key = substr($base, 7);
            if($key === 'time' && $param !== null){
                $fmt = $param;
                // Allow <...> bracket style too
                if(str_starts_with($fmt, '<') && str_ends_with($fmt, '>')){
                    $fmt = substr($fmt, 1, -1);
                }
                return date($fmt);
            }
            if($key === 'online' && $param !== null){
                $server = Server::getInstance();
                $world = $server->getWorldManager()->getWorldByName($param);
                if($world === null){ return '0'; }
                $n = 0;
                foreach($server->getOnlinePlayers() as $p){
                    if($p->getWorld()->getId() === $world->getId()) $n++;
                }
                return (string) $n;
            }
        }
        return null;
    }

    private function handlePlayerPlaceholder(string $key, Player $player) : ?string{
        switch($key){
            case 'name':
                return $player->getName();
            case 'health':
                return (string) ((int) $player->getHealth());
            case 'gamemode':
                return $this->gameModeToString($player->getGamemode());
            case 'x':
                return (string) round($player->getPosition()->getX(), 2);
            case 'y':
                return (string) round($player->getPosition()->getY(), 2);
            case 'z':
                return (string) round($player->getPosition()->getZ(), 2);
            case 'world':
                return $player->getWorld()->getFolderName();
            case 'ping':
                return (string) $player->getNetworkSession()->getPing();
            case 'is_op':
                return Server::getInstance()->isOp($player->getName()) ? 'yes' : 'no';
            case 'session_time':
                $secs = Main::getInstance()->getSessionSeconds($player);
                $h = intdiv($secs, 3600); $m = intdiv($secs % 3600, 60); $s = $secs % 60;
                return sprintf('%02d:%02d:%02d', $h, $m, $s);
            default:
                // Items and armor placeholders (basic examples)
                if($key === 'item_in_hand_name'){
                    $item = $player->getInventory()->getItemInHand();
                    return $item->isNull() ? '' : $item->getVanillaName();
                }
                if($key === 'item_in_offhand_name'){
                    $item = $player->getOffHandInventory()->getItem(0);
                    return $item->isNull() ? '' : $item->getVanillaName();
                }
                if($key === 'armor_helmet_name'){
                    $it = $player->getArmorInventory()->getHelmet();
                    return $it === null || $it->isNull() ? '' : $it->getVanillaName();
                }
                if($key === 'armor_chestplate_name'){
                    $it = $player->getArmorInventory()->getChestplate();
                    return $it === null || $it->isNull() ? '' : $it->getVanillaName();
                }
                if($key === 'armor_leggings_name'){
                    $it = $player->getArmorInventory()->getLeggings();
                    return $it === null || $it->isNull() ? '' : $it->getVanillaName();
                }
                if($key === 'armor_boots_name'){
                    $it = $player->getArmorInventory()->getBoots();
                    return $it === null || $it->isNull() ? '' : $it->getVanillaName();
                }
        }
        return null;
    }

    private function handleServerPlaceholder(string $key) : ?string{
        $server = Server::getInstance();
        switch($key){
            case 'name':
                return $server->getMotd();
            case 'online':
                return (string) count($server->getOnlinePlayers());
            case 'max_players':
                return (string) $server->getMaxPlayers();
            case 'version':
                return $server->getPocketMineVersion();
            case 'tps':
                return number_format($server->getTicksPerSecond(), 2);
            case 'tps_1':
                return number_format($server->getTicksPerSecondAverage(1), 2);
            case 'tps_5':
                return number_format($server->getTicksPerSecondAverage(5), 2);
            case 'tps_15':
                return number_format($server->getTicksPerSecondAverage(15), 2);
            case 'tps_1_colored':
                return $this->colorTps($server->getTicksPerSecondAverage(1));
            case 'tps_5_colored':
                return $this->colorTps($server->getTicksPerSecondAverage(5));
            case 'tps_15_colored':
                return $this->colorTps($server->getTicksPerSecondAverage(15));
            case 'uptime':
                $uptime = (int) (microtime(true) - \pocketmine\START_TIME);
                $h = intdiv($uptime, 3600); $m = intdiv($uptime % 3600, 60); $s = $uptime % 60;
                return sprintf('%02d:%02d:%02d', $h, $m, $s);
            default:
                // server_time_<format>
                if(str_starts_with($key, 'time_')){
                    $format = substr($key, 5);
                    // If wrapped like <...>, strip brackets
                    if(str_starts_with($format, '<') && str_ends_with($format, '>')){
                        $format = substr($format, 1, -1);
                    }
                    // Support PHP date() formats directly
                    return date($format);
                }
                // server_online_<world>
                if(str_starts_with($key, 'online_')){
                    $worldName = substr($key, 7);
                    $world = $server->getWorldManager()->getWorldByName($worldName);
                    if($world === null){
                        return '0';
                    }
                    $n = 0;
                    foreach($server->getOnlinePlayers() as $p){
                        if($p->getWorld()->getId() === $world->getId()) $n++;
                    }
                    return (string) $n;
                }
                // server_countdown_<format>_<time>
                if(str_starts_with($key, 'countdown_')){
                    $rest = substr($key, 10);
                    $parts = explode('_', $rest, 2);
                    if(count($parts) === 2){
                        [$format, $timeStr] = $parts;
                        $target = is_numeric($timeStr) ? (int)$timeStr : strtotime($timeStr);
                        if($target !== false){
                            $diff = max(0, $target - time());
                            $h = intdiv($diff, 3600); $m = intdiv($diff % 3600, 60); $s = $diff % 60;
                            $map = [
                                'HH' => sprintf('%02d', $h),
                                'mm' => sprintf('%02d', $m),
                                'ss' => sprintf('%02d', $s),
                            ];
                            return strtr($format, $map);
                        }
                    }
                }
        }
        return null;
    }

    private function gameModeToString(GameMode $gm) : string{
        return match(true){
            $gm->equals(GameMode::SURVIVAL()) => 'survival',
            $gm->equals(GameMode::CREATIVE()) => 'creative',
            $gm->equals(GameMode::ADVENTURE()) => 'adventure',
            $gm->equals(GameMode::SPECTATOR()) => 'spectator',
            default => 'unknown'
        };
    }

    private function colorTps(float $tps) : string{
        $color = $tps >= 19.0 ? TextFormat::GREEN : ($tps >= 15.0 ? TextFormat::YELLOW : TextFormat::RED);
        return $color . number_format($tps, 2) . TextFormat::RESET;
    }
}
