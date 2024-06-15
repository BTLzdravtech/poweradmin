<?php

/*  Poweradmin, a friendly web-based admin tool for PowerDNS.
 *  See <https://www.poweradmin.org> for more details.
 *
 *  Copyright 2007-2010 Rejo Zenger <rejo@zenger.nl>
 *  Copyright 2010-2024 Poweradmin Development Team
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

namespace Poweradmin;

class LegacyLocale
{
    private array $supportedLocales;
    private string $localeDirectory;

    public function __construct(array $supportedLocales, string $localeDirectory)
    {
        $this->supportedLocales = $supportedLocales;
        $this->localeDirectory = $localeDirectory;
    }

    public function setLocale(string $locale): void
    {
        if (!in_array($locale, $this->supportedLocales)) {
            error_log("The provided locale '{$locale}' is not supported. Please choose a supported locale.");
            return;
        }

        if ($locale == 'en_EN' || $locale == 'en_US.UTF-8') {
            return;
        }

        $locales = [
            $locale . '.UTF-8',
            $locale . '.utf8',
            $locale,
        ];

        if (!setlocale(LC_ALL, $locales)) {
            error_log("Failed to set locale '{$locale}'. Selected locale may be unsupported on this system.");
            return;
        }

        if (!is_dir($this->localeDirectory) || !is_readable($this->localeDirectory)) {
            error_log("The directory '{$this->localeDirectory}' does not exist or is not readable.");
            return;
        }

        $gettext_domain = 'messages';
        bindtextdomain($gettext_domain, $this->localeDirectory);
        bind_textdomain_codeset($gettext_domain, 'utf-8');
        textdomain($gettext_domain);
        @putenv('LANG=' . $locale);
        @putenv('LANGUAGE=' . $locale);
    }
}
