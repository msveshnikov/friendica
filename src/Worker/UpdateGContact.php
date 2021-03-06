<?php
/**
 * @copyright Copyright (C) 2020, Friendica
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

namespace Friendica\Worker;

use Friendica\Core\Logger;
use Friendica\DI;
use Friendica\Model\GContact;

class UpdateGContact
{
	/**
	 * Update global contact via probe
	 * @param string  $url            Global contact url
	 * @param string  $command
	 * @param integer $following_gcid gcontact ID of the contact that is followed by this one
	 * @param integer $follower_gcid  gcontact ID of the contact that is following this one
	 */
	public static function execute(string $url, string $command = '', int $following_gcid = 0, int $follower_gcid = 0)
	{
		$force = ($command == "force");

		$success = GContact::updateFromProbe($url, $force);

		Logger::info('Updated from probe', ['url' => $url, 'force' => $force, 'success' => $success]);

		if ($success && DI::config()->get('system', 'gcontact_discovery')) {
			GContact::discoverFollowers($url, $following_gcid, $follower_gcid);
		}
	}
}
