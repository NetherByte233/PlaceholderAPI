# Developer Guide

API entry: `PlaceholderAPI/src/NetherByte/PlaceholderAPI/PlaceholderAPI.php`

- Built-in placeholders are provided by the plugin itself and are auto-loaded.
- Third-party placeholders must be supplied via a Provider and installed by server admins using `/papi download <id>`.
- Direct expansion registration is disabled (see `PlaceholderAPI::registerExpansion()` deprecation note in code).

## Parsing text (consumers)

```php
use NetherByte\PlaceholderAPI\PlaceholderAPI;

$msg = PlaceholderAPI::parse("Welcome %player_name% to %server_name%!", $player);
$tps = PlaceholderAPI::get('server_tps');
```

## Implementing placeholders for your plugin (Provider-only)

Provide placeholders by exposing a Provider that can construct your Expansion(s) on demand. Admins will install them with `/papi download <identifier>`.

```php
use NetherByte\PlaceholderAPI\provider\Provider;
use NetherByte\PlaceholderAPI\expansion\Expansion;
use pocketmine\player\Player;

final class MyExpansion extends Expansion{
    public function getName() : string { return 'MyPlugin Placeholders'; }
    public function getAuthor() : ?string { return 'YourName'; }
    public function getVersion() : ?string { return '1.0.0'; }
    public function getDescription() : ?string { return 'Placeholders for MyPlugin'; }

    // Optional namespace to reduce collisions
    public function getIdentifierPrefix() : ?string { return 'myplugin_'; }
    public function getUpdateIntervalSeconds() : int { return 1; }

    public function onRequestWithParams(string $base, ?string $param, ?Player $player) : ?string{
        if($base === 'myplugin_points'){
            return $param === 'formatted' ? '42 pts' : '42';
        }
        return null;
    }

    public function onRequest(string $identifier, ?Player $player) : ?string{
        return $identifier === 'myplugin_points' ? '42' : null;
    }
}

final class MyProvider implements Provider{
    public function getName() : string { return 'MyPlugin'; }
    public function listExpansions() : array { return ['myplugin']; } // identifier chosen by you
    public function provide(string $identifier) : ?Expansion{
        return $identifier === 'myplugin' ? new MyExpansion($this) : null;
    }
}

// In your onEnable():
// PlaceholderAPI::registerProvider(new MyProvider());
```

Admin workflow for your users:
- `/papi providers` to see your provider and its identifiers
- `/papi download myplugin` to install your expansion
- Placeholders like `%myplugin_points%` now work (and persist across restarts)

## Best Practices

- Use a short, unique identifier (e.g., `myplugin`, `economy`, `stats`).
- Group your keys under a clear prefix (`myplugin_points`, `myplugin_rank`).
- Prefer param style (`identifier:param`) for clarity and future compatibility.
- Keep computations light; if heavy, use `getUpdateIntervalSeconds()` or precompute.
- If you depend on PlaceholderAPI, add `softdepend: [PlaceholderAPI]` in your plugin.yml and register your Provider in `onEnable()`.

## Navigation
<div class="grid cards" markdown>
- :material-school: **Using PlaceholderAPI**
    - [> Using PlaceholderAPI](dev-using-placeholderapi.md)

- :material-console: **Creating Expansions**
    - [> Creating Expansions](dev-creating-expansion.md)
</div>