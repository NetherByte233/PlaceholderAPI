# Using PlaceholderAPI (for consumers)

This page shows how to use PlaceholderAPI from your plugin when you want to parse messages or fetch single values.

> Note: Only built-in placeholders and placeholders from installed providers will resolve.
> If a third-party plugin exposes a Provider, server admins must run `/papi download <id>` once to enable that plugin’s placeholders.

## Parse text with placeholders

```php
use NetherByte\PlaceholderAPI\PlaceholderAPI;
use pocketmine\player\Player;

/** @var Player|null $player */
$msg = "Welcome %player_name% to %server_name%! TPS: %server_tps%";
$out = PlaceholderAPI::parse($msg, $player);
```

Notes:
- Player can be `null` for server-only placeholders.
- Unknown placeholders remain unchanged.

## Get a single value

```php
$tps = PlaceholderAPI::get('server_tps');
$time = PlaceholderAPI::get('server_time:Y-m-d H:i'); // parameterized style
```

## Parameterized placeholders

You can pass a parameter with `identifier:param`.

Examples:
- `server_time:<Y-m-d H:i>`
- `server_online:world`

The plugin also accepts underscore-style for backwards compatibility:
- `server_time_<format>`
- `server_online_<world>`

## Debugging

- Use `/papi info <identifier[:param]> [player]` to see:
  - Which expansion handles it
  - The resolved value
  - Expansion metadata (author, version, description)

## Performance tips

- Parsing once and reusing the result is cheaper than parsing every tick.
- If you poll frequently (e.g., scoreboards), design your own cache where possible.
- The API supports light caching per expansion when the expansion declares an update interval.

## Setting Placeholder in your plugin (practical examples)

Below are end-to-end examples showing how to integrate PlaceholderAPI output into common plugin scenarios.

### 1) Custom join message including the player's primary group

??? example "This example listens for PlayerJoin and broadcasts a formatted message that includes the player's primary group via either NetherPerms or PocketVault."
    ```php
    <?php

    use NetherByte\PlaceholderAPI\PlaceholderAPI;
    use pocketmine\event\Listener;
    use pocketmine\event\player\PlayerJoinEvent;
    use pocketmine\plugin\PluginBase;

    final class JoinMessagePlugin extends PluginBase implements Listener{
        protected function onEnable() : void{
            $this->getServer()->getPluginManager()->registerEvents($this, $this);
        }

        /** @priority MONITOR */
        public function onJoin(PlayerJoinEvent $event) : void{
            $p = $event->getPlayer();

            // Prefer NetherPerms if installed; fallback to PocketVault
            // Placeholders:
            //   %netherperms_primary_group%
            //   %pocketvault_group%
            $template = "&aWelcome &e%player_name% &7[&b%netherperms_primary_group%%pocketvault_group%&7]&a!";

            // If NetherPerms is not installed, %netherperms_primary_group% resolves to empty string.
            // If PocketVault is not installed, %pocketvault_group% resolves to empty string.
            $message = PlaceholderAPI::parse($template, $p);
            $this->getServer()->broadcastMessage($message);
        }
    }
    ```


Tips:
- Ensure the relevant provider is installed:
  - NetherPerms: `/papi download netherperms`
  - PocketVault: `/papi download pocketvault`

### 2) Scoreboard lines using primary group, money, name, and ping

If your scoreboard plugin supports placeholders in its configuration, you can reference PlaceholderAPI keys directly. Below is a generic YAML-style example of lines:

??? example "Scoreboard config using placeholders"
    ```yaml
    scoreboard:
      title: "&6Server &7| &a%player_name%"
      lines:
        - "&fGroup: &b%netherperms_primary_group%%pocketvault_group%"
        - "&fMoney: &a%pocketvault_eco_balance_formatted%"
        - "&fPing: &e%player_ping% ms"
        - "&fWorld: &d%player_world%"
    ```

Notes:
- Primary group can come from either `%netherperms_primary_group%` or `%pocketvault_group%` depending on which provider is installed.
- Use `%pocketvault_eco_balance_formatted%` for currency symbol/commas if configured by your economy provider, or `%pocketvault_eco_balance%` for raw numeric.

### 3) Using placeholders in a GUI/Form title, content, or button tooltip

You can parse strings before sending a form to the player. Example using a common FormAPI-like interface:

??? example "Runtime form parsing with placeholders"
    ```php
    use NetherByte\PlaceholderAPI\PlaceholderAPI;
    use dktapps\pmforms\MenuForm; // Example; adapt to your forms library
    use dktapps\pmforms\MenuOption;

    $player = /* Player instance */;

    $title = PlaceholderAPI::parse("&bProfile of %player_name%", $player);
    $content = PlaceholderAPI::parse("&7Group: &a%netherperms_primary_group%%pocketvault_group%\n&7Money: &e%pocketvault_eco_balance_formatted%\n&7Ping: &6%player_ping% ms", $player);

    $button1 = new MenuOption(PlaceholderAPI::parse("Open Menu &8(&b%nethermenus_opened_menu_name%&8)", $player));
    $button2 = new MenuOption(PlaceholderAPI::parse("Last Menu: &d%nethermenus_last_menu_name%", $player));

    $form = new MenuForm($title, $content, [$button1, $button2], function(){});
    $player->sendForm($form);
    ```

??? example "Menus config with placeholders in UI"
    ```yaml
    menus:
      profile:
        title: "&bProfile &7| &f%player_name%"
        buttons:
          group:
            name: "&aGroup: &f%netherperms_primary_group%%pocketvault_group%"
            lore:
              - "&7Money: &e%pocketvault_eco_balance_formatted%"
              - "&7Ping: &6%player_ping% ms"
    ```
    
