<?php
/*  Poweradmin, a friendly web-based admin tool for PowerDNS.
 *  See <https://www.poweradmin.org> for more details.
 *
 *  Copyright 2007-2010 Rejo Zenger <rejo@zenger.nl>
 *  Copyright 2010-2023 Poweradmin Development Team
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * Plugin API
 *
 * Dynamically add behavior by putting code in the plugins directory
 * of the application root.  For the loader to include a plugin,
 * there must be a file named:
 *
 *     plugins/[name]/[name].plugin.php
 *
 * Further imports, functions, or definitions can be done from
 * that top-level script.
 */
$hook_listeners = array();

/**
 * Register function to be executed for the given hook
 *
 * @param string $hook
 * @param mixed $function
 */
function add_listener(string $hook, $function) {
    if (!$hook || !$function) {
        trigger_error('add_listener requires both a hook name and a function', E_USER_ERROR);
    }
    global $hook_listeners;
    $hook_listeners [$hook] [] = $function;
}

/**
 * Execute a hook, call registered listener functions
 */
function do_hook() {
    global $hook_listeners;
    $argc = func_num_args();
    $argv = func_get_args();
    if ($argc < 1) {
        trigger_error('Missing argument in do_hook', E_USER_ERROR);
    }

    $hook_name = array_shift($argv);

    if (!isset($hook_listeners [$hook_name])) {
        return false;
    }

    foreach ($hook_listeners [$hook_name] as $func) {
        return call_user_func_array($func, $argv);
    }

    return false;
}

require_once 'inc/plugins/auth_local/auth_local.plugin.php';
require_once 'inc/plugins/users_local/users_local.plugin.php';
