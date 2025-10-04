# Changelog

## Recent

- Added parameterized placeholders (`identifier:param`) with fallback to underscore style.
- Added expansion metadata (`author`, `version`, `description`).
- Added optional caching via `getUpdateIntervalSeconds()`.
- New `/papi info` command for debugging.
- Auto-reinstall of installed expansions at startup (no need for `/papi reload`).

## Earlier

- Initial release with built-in player/server placeholders and provider system.
