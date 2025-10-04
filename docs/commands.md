# Commands

## Admin Commands

- `/papi list` — Show loaded expansions.
- `/papi providers` — List providers and their identifiers.
- `/papi download <identifier>` — Install an expansion by identifier.
- `/papi reload` — Reload expansions from installed list.
- `/papi info <identifier[:param]> [player]` — Inspect handler/value/meta.

Usage: `/papi <list|providers|download|reload|info>`

## Permissions

- `placeholderapi.use` (default: true)
- `placeholderapi.command.base` (default: op)
- `placeholderapi.command.list` (default: op)
- `placeholderapi.command.providers` (default: op)
- `placeholderapi.command.download` (default: op)
- `placeholderapi.command.reload` (default: op)
- `placeholderapi.command.info` (default: op)

## Examples

- Install NetherPerms expansion: `/papi download netherperms`
- Check providers: `/papi providers`
- View placeholder info: `/papi info netherperms_primary_group <player>`
