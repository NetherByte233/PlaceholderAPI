# Getting Started

This guide helps server admins install, run, and verify PlaceholderAPI.

## Install

1) Place `PlaceholderAPI/` or its PHAR in your `plugins/` folder.
2) Start the server.
3) Check console: you should see PlaceholderAPI enabled with expansions count.

## Verify

- Run `/papi list` — you should see `Builtin` loaded.
- Try a message using placeholders (depends on consuming plugin) or test via `/papi info`:
  - `/papi info server_tps`
  - `/papi info server_time:<H:i>`

## Installing Expansions (optional)

If another plugin exposes a Provider:
1) Run `/papi providers` to see available identifiers.
2) Install one: `/papi download <identifier>`
3) On restart, installed identifiers auto-reinstall.

## After Restart

- You no longer need `/papi reload`. Installed expansions are restored automatically a tick after startup.

## Troubleshooting

- Placeholders literal/unresolved: check `/papi list`, or install via `/papi download <id>`.
- Player placeholder empty: you parsed without a player context.
- Providers missing: ensure the provider plugin is loaded and registers on enable.
