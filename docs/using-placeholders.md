# Using Placeholders

You can embed placeholders anywhere a plugin parses text with PlaceholderAPI.

Example message:
```
Welcome %player_name% to %server_name%! TPS: %server_tps%
```

- Unknown placeholders remain unchanged.
- Player placeholders return an empty string when no player context is provided.

## Parameterized placeholders

Besides `%identifier%`, you can pass a parameter with `%identifier:param%`.

Examples:
- `%server_time:<Y-m-d H:i>%` — PHP date() format
- `%server_online:world%` — online players in a given world

Backward-compatible underscore style still works:
- `%server_time_<format>%`
- `%server_online_<world>%`

## Tips

- Use `/papi info <identifier[:param]> [player]` to inspect handler/value.
- If a third-party placeholder doesn’t resolve, install its provider with `/papi download <id>`.
