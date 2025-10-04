# Creating a PlaceholderExpansion (Provider-only)

Third-party plugins must expose a `Provider` that supplies one or more `Expansion`s. Admins install them using `/papi download <id>`.

## 1) Implement your Expansion

```php
use NetherByte\PlaceholderAPI\expansion\Expansion;
use pocketmine\player\Player;

final class MyExpansion extends Expansion{
    public function getName() : string { return 'MyPlugin Placeholders'; }
    public function getAuthor() : ?string { return 'YourName'; }
    public function getVersion() : ?string { return '1.0.0'; }
    public function getDescription() : ?string { return 'Placeholders for MyPlugin'; }

    // Optional: limit to a prefix to reduce collisions
    public function getIdentifierPrefix() : ?string { return 'myplugin_'; }
    // Optional: lightweight API-level caching
    public function getUpdateIntervalSeconds() : int { return 1; }

    // Param-aware (identifier:param)
    public function onRequestWithParams(string $base, ?string $param, ?Player $player) : ?string{
        if($base === 'myplugin_points'){
            return $param === 'formatted' ? '42 pts' : '42';
        }
        return null;
    }

    // Legacy underscore fallback
    public function onRequest(string $identifier, ?Player $player) : ?string{
        return $identifier === 'myplugin_points' ? '42' : null;
    }
}
```

## 2) Implement your Provider

```php
use NetherByte\PlaceholderAPI\provider\Provider;
use NetherByte\PlaceholderAPI\expansion\Expansion;

final class MyProvider implements Provider{
    public function getName() : string { return 'MyPlugin'; }
    public function listExpansions() : array { return ['myplugin']; }
    public function provide(string $identifier) : ?Expansion{
        return $identifier === 'myplugin' ? new MyExpansion($this) : null;
    }
}
```

## 3) Register your Provider in onEnable()

```php
use NetherByte\PlaceholderAPI\PlaceholderAPI;

protected function onEnable() : void{
    if(class_exists(PlaceholderAPI::class)){
        PlaceholderAPI::registerProvider(new MyProvider());
    }
}
```

## 4) Admin installation workflow

- `/papi providers` — confirms your provider and lists identifiers.
- `/papi download myplugin` — installs your expansion.
- Restart persists installed identifiers automatically.

## Best practices

- Keep heavy work out of `onRequest()`; precompute or cache using `getUpdateIntervalSeconds()`.
- Use a short identifier (e.g., `myplugin`, `economy`, `stats`) and a clear key namespace (`myplugin_points`).
- Return `null` for unknown identifiers; return `''` when you intentionally want an empty display.
