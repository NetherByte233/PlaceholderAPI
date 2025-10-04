# Placeholder List

This page lists built-in placeholders available out of the box. Third-party placeholders are available after installing their Providers with `/papi download <id>`.

## A–Z Index
[ A ](#a) · [ B ](#b) · [ C ](#c) · [ D ](#d) · [ E ](#e) · [ F ](#f) · [ G ](#g) · [ H ](#h) · [ I ](#i) · [ J ](#j) · [ K ](#k) · [ L ](#l) · [ M ](#m) · [ N ](#n) · [ O ](#o) · [ P ](#p) · [ Q ](#q) · [ R ](#r) · [ S ](#s) · [ T ](#t) · [ U ](#u) · [ V ](#v) · [ W ](#w) · [ X ](#x) · [ Y ](#y) · [ Z ](#z)

## A {#a}
No placeholder

## B {#b}
No placeholder

## C {#c}
No placeholder

## D {#d}
No placeholder

## E {#e}
No placeholder

## F {#f}
No placeholder

## G {#g}
No placeholder

## H {#h}
No placeholder

## I {#i}
No placeholder

## J {#j}
No placeholder

## K {#k}
No placeholder

## L {#l}
No placeholder

## M {#m}
No placeholder

## N {#n}
- [NetherMenus](#nethermenus)
- [NetherPerms](#netherperms)
## O {#o}
No placeholder

## P {#p}
- [Player](#player)
- [PocketVault](#pocketvault)

## Q {#q}
No placeholder

## R {#r}
No placeholder

## S {#s}
- [Server](#server)

## T {#t}
No placeholder

## U {#u}
No placeholder

## V {#v}
No placeholder

## W {#w}
No placeholder

## X {#x}
No placeholder

## Y {#y}
No placeholder

## Z {#z}
No placeholder

---
---
### NetherMenus
- Identifier: `nethermenus`
- Install: 
```yaml
/papi download nethermenus
```
- Placeholders:
```yaml
%nethermenus_opened_menu%
%nethermenus_opened_menu_name%
%nethermenus_is_in_menu%
%nethermenus_last_menu%
%nethermenus_last_menu_name%
```
---
---
### NetherPerms
- Identifier: `netherperms`
- Install: 
```yaml
/papi download netherperms
```
- Placeholders:
```yaml
# Basic user info
%netherperms_primary_group%
%netherperms_primary_group_name%
%netherperms_prefix%
%netherperms_suffix%
%netherperms_groups%
%netherperms_meta_<key>%

# Group relations
%netherperms_inherited_groups%
%netherperms_in_group_<group>%               # true/false
%netherperms_inherits_group_<group>%         # true/false

# Permission checks
%netherperms_has_permission_<node>%          # true/false (direct only)
%netherperms_inherits_permission_<node>%     # true/false (effective but not direct)
%netherperms_check_permission_<node>%        # true/false (effective)

# Tracks
%netherperms_on_track_<track>%               # true/false (is primary group on this track)
%netherperms_has_groups_on_track_<track>%    # true/false
%netherperms_current_group_on_track_<track>%
%netherperms_next_group_on_track_<track>%
%netherperms_previous_group_on_track_<track>%
%netherperms_first_group_on_tracks_<t1,t2,...>%
%netherperms_last_group_on_tracks_<t1,t2,...>%

# Weight-based
%netherperms_highest_group_by_weight%
%netherperms_lowest_group_by_weight%
%netherperms_highest_inherited_group_by_weight%
%netherperms_lowest_inherited_group_by_weight%

# Temporary permission expiry (seconds remaining)
%netherperms_expiry_time_<node>%
%netherperms_inherited_expiry_time_<node>%
%netherperms_group_expiry_time_<group>%              # not supported, returns empty
%netherperms_inherited_group_expiry_time_<group>%    # not supported, returns empty
```
---
---
### Player
- Identifier: `player`
- Install: No need to install builtin
- Placeholders:
```yaml
%player_name%
%player_health%
%player_gamemode%
%player_x%, %player_y%, %player_z%
%player_world%
%player_ping%
%player_is_op%
%player_session_time%              # HH:MM:SS since login
%player_item_in_hand_name%
%player_item_in_offhand_name%
%player_armor_helmet_name%
%player_armor_chestplate_name%
%player_armor_leggings_name%
%player_armor_boots_name%
%player_ping_<playername>%         # lookup another player's ping
```
---
---
### PocketVault
- Identifier: `pocketvault`
- Install: 
```yaml
/papi download pocketvault
```
- Placeholders:
```yaml
# Economy (via linked economy plugin)
%pocketvault_eco_balance%
%pocketvault_eco_balance_formatted%
%pocketvault_eco_balance_commas%
%pocketvault_eco_balance_fixed%
%pocketvault_eco_balance_<dp>dp%   # e.g., %pocketvault_eco_balance_0dp%

# Permissions/Chat (via linked permissions/chat provider)
%pocketvault_group%
%pocketvault_group_capital%
%pocketvault_groups%
%pocketvault_groups_capital%
%pocketvault_prefix%
%pocketvault_suffix%
%pocketvault_groupprefix%
%pocketvault_groupsuffix%
%pocketvault_groupprefix_<n>%      # 1-based index in user's groups
%pocketvault_groupsuffix_<n>%      # 1-based index in user's groups
%pocketvault_hasgroup_<group>%     # yes/no
%pocketvault_inprimarygroup_<group>% # yes/no
```
---

---
### Server
- Identifier: `server`
- Install: No need to install builtin
- Placeholders:
```yaml
%server_name% (MOTD)
%server_online%
%server_max_players%
%server_version%
%server_tps%
%server_tps_1%
%server_tps_5%
%server_tps_15%
%server_tps_1_colored%
%server_tps_5_colored%
%server_tps_15_colored%
%server_uptime% (HH:MM:SS)
%server_time:<format>%              # e.g., %server_time:<Y-m-d H:i>%
%server_time_<format>%              # e.g., %server_time_Y-m-d%
%server_online:<world>%
%server_online_<world>%
%server_countdown_<format>_<time>%  # e.g., HH_mm_ss and unix timestamp or strtotime string
