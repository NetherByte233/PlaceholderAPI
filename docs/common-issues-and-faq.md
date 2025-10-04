# Common Issues & FAQ

## Common Issues

- __Placeholders show literally (e.g., `%netherperms_prefix%`)__
  - The expansion isn’t installed. Run `/papi providers` to find the identifier, then `/papi download <id>`.
  - Confirm with `/papi list` after installing.

- __Placeholders work only after `/papi reload`__
  - The plugin auto-reinstalls installed expansions 1 tick after startup. Ensure you installed with `/papi download <id>` at least once. If a provider registers very late, increase the delay (ask plugin dev to make it configurable).

- __Player placeholders are empty__
  - You parsed without a player context. Pass a `Player` to `PlaceholderAPI::parse()` or `PlaceholderAPI::get()` when required.

- __Which plugin handles this placeholder?__
  - Use `/papi info <identifier[:param]> [player]`. It shows value, the expansion name, and metadata.

- __Performance concerns (scoreboards, frequent updates)__
  - Cache your rendered texts where possible.
  - Many expansions expose a short update interval to cache values briefly. Avoid parsing every tick if not needed.

- __Conflicts or collisions__
  - Well-designed expansions declare a prefix via `getIdentifierPrefix()` to avoid collisions. Prefer using fully namespaced identifiers (e.g., `myplugin_points`).

## FAQ

- __Do I need Providers?__
  - Built-in placeholders are always available. Third-party placeholders require a Provider and must be installed with `/papi download <id>`.

- __Can I pass parameters to placeholders?__
  - Yes. Use `identifier:param`, e.g., `%server_time:<Y-m-d H:i>%`. Underscore style like `%server_time_<format>%` is also supported.

- __Do installed expansions persist after restart?__
  - Yes. The plugin remembers your installed identifiers and reinstalls them automatically on startup.

- __How do I see a list of available third-party identifiers?__
  - `/papi providers`.

- __How do I enable a third-party placeholder?__
  - `/papi download <identifier>` (one-time). After that, it is available immediately and on future restarts.

- __How do I get help?__
  - Check `/papi info`, the logs, and this wiki. If an issue persists, open an issue with details: server version, plugin list, steps to reproduce, and relevant logs.
