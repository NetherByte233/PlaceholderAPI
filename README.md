# PlaceholderAPI Wiki

Welcome on the Wiki branch!

This branch is home of the [PlaceholderAPI Wiki's][wiki] source.  
It allows us to properly manage the wiki and it allows you to contribute changes to it.

## How to contribute

Thank you for helping improve the Wiki. This document explains how to add or update documentation for:
- Built-in placeholders provided by PlaceholderAPI
- Provider/Expansion plugins that add new placeholders
- Plugins that consume placeholders in their configuration or UI

### 1) What belongs in this wiki
- Built-in placeholders: Player, Server, and any other core expansions that ship with `PlaceholderAPI`.
- Provider/Expansion plugins: Each plugin that registers placeholders via our API should have a section with its identifier, installation instructions, and the complete list of placeholders.
- Plugins using placeholders: Popular plugins that render or parse placeholders (scoreboards, menus, chat formats, etc.) with short examples.

### 2) Where to put things
- `docs/placeholder-list.md`: The canonical list of placeholders. Add or correct entries under the appropriate plugin/provider section.
- `docs/plugins-using.md`: Add or update entries for plugins that use placeholders, with minimal working examples.
- `mkdocs.yml`: Update the navigation if you add a new page.

### 3) Documenting a Provider/Expansion (new placeholders)
When a plugin provides placeholders through our API, document it as follows:
1. Identify the provider/expansion identifier and placeholders by inspecting its code (commonly an `Expansion` class). Include only what is actually implemented.
2. In `docs/placeholder-list.md`, add a section with:
   - Title: `### PluginName`
   - Identifier: backticked identifier (e.g., `netherperms`, `pocketvault`)
   - Install: fenced block with `/papi download <identifier>` or a note if bundled
   - Placeholders: fenced `yaml` block listing all placeholders in percent form
   - Notes: any alias support, parameter formats, return conventions (e.g., `true/false` vs `yes/no`), and edge cases
3. Keep placeholders in lowercase, use underscores, and show parameters using `<param>` or `<param1,param2>` within the percent-wrapped placeholder (e.g., `%id_key_<param>%`).
4. If the provider supports parameterized placeholders via both `%id_key:<param>%` and `%id_key_<param>%`, document both forms.

Example section template:
```markdown
### ExampleProvider
- Identifier: `example`
- Install:
```yaml
/papi download example
```
- Placeholders:
```yaml
%example_stat%
%example_stat_formatted%
%example_lookup_<name>%
%example_time:<format>%
```
- Notes:
  - `%example_time:<format>%` also supports `%example_time_<format>%`.
  - Returns empty string when data is unavailable.
```

### 4) Documenting Built-in placeholders
- Update the Player and Server sections in `docs/placeholder-list.md` to reflect the current implementation in `PlaceholderAPI`'s builtin expansion(s). Include new keys and parameter behaviors, and remove keys that no longer exist.
- Keep short comments to explain formats, e.g., `HH:MM:SS` or date format examples.

### 5) Documenting plugins that use placeholders
In `docs/plugins-using.md`:
- Add a short description of how the plugin consumes placeholders.
- Provide a minimal, copy-paste example of configuration where placeholders are parsed.
- Link to the plugin's repository/page.

Example snippet:
```yaml
# NetherMenus example
menus:
  main:
    title: "&aWelcome, %player_name%"
```

### 6) Verification checklist
Before opening a PR, please verify:
- Placeholders listed match the exact identifiers implemented by the provider (check the code).
- Parameterized formats are correct (e.g., `%server_time:<format>%`, `%server_time_<format>%`).
- Return values are correctly described (e.g., empty string vs `0`, `true/false` vs `yes/no`).
- Installation instructions are valid (e.g., `/papi download <id>` or note if built-in).
- Examples are minimal and working.

Optional runtime checks (if you can run a test server):
- `/papi list` shows the provider and identifiers
- `/papi parse %identifier_key%` returns expected values

### 7) Pull Request guidelines
- Keep changes focused and scoped to one topic (e.g., a single provider update) when possible.
- Include a brief description of how you verified the placeholders and link to relevant source files.
- Follow formatting (see below) and ensure Markdown builds with MkDocs locally if you can.

### 8) Formatting and style
- Use backticks for identifiers and inline code.
- Use fenced code blocks for placeholder lists; prefer `yaml` language hint inside the block for readability.
- Keep placeholder keys lowercase with underscores.
- Group related placeholders together and add brief comments sparingly.
- When deprecating placeholders, note it clearly and, if possible, provide a replacement.

### 9) Repository structure and workflow

This project uses two branches in the PlaceholderAPI GitHub repository:
- `main`: the plugin source code
- `wiki`: the Markdown documentation for this wiki (what you are viewing/editing here)

Always target the correct branch for your change:
- Code changes ➜ open PR against `main`
- Wiki/documentation changes ➜ open PR against `wiki`

### 10) Step-by-step: contribute via GitHub

1) Fork the repository
- Visit the GitHub repository and click "Fork" to create your copy under your account.

2) Clone your fork locally
```bash
# Replace <your-username> with your GitHub username
git clone https://github.com/<your-username>/PlaceholderAPI.git
cd PlaceholderAPI
```

3) Add the upstream remote (original repository)
```bash
git remote add upstream https://github.com/NetherByte233/PlaceholderAPI.git
```

4) Choose the correct working branch
- For wiki changes:
```bash
git fetch upstream
git checkout wiki
git merge upstream/wiki   
```

5) Create a feature branch
```bash
# Name your branch clearly, e.g., docs/update-placeholder-list or fix/readme-typo
git switch -c docs/update-placeholder-list
```

6) Make your changes and commit
```bash
git add .
git commit -m "docs: update placeholder list for NetherPerms and PocketVault"
```

7) Push your branch to your fork
```bash
git push -u origin docs/update-placeholder-list
```

8) Open a Pull Request (PR)
- On GitHub, open a PR from your branch in your fork to the original repository.
- Ensure the target branch matches your change type:
  - Documentation ➜ base branch `wiki`
  - Code ➜ base branch `main`
- Fill out the PR description, including what you changed and how you verified it.

9) Keep your branch up to date (optional)
```bash
git fetch upstream
git rebase upstream/wiki   # if your PR targets wiki
# or
git rebase upstream/main   # if your PR targets main
```

10) Address review feedback
- Push follow-up commits to the same branch; the PR will update automatically.

Tips
- Keep PRs small and focused.
- Reference files you changed, e.g., `docs/placeholder-list.md` or `docs/plugins-using.md`.

### 11) Ready to submit?
- Commit your changes on a feature branch.
- Open a Pull Request against the wiki repository.
- A maintainer will review and request changes if needed.

---

[wiki]: https://netherbyte233.github.io/PlaceholderAPI/
