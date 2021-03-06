<?php

namespace App\ControllerFunctions;

use App\Album;
use App\ModelFunctions\SessionFunctions;
use App\Photo;

class ReadAccessFunctions
{
	/**
	 * @var SessionFunctions
	 */
	private $sessionFunctions;

	/**
	 * @param SessionFunctions $sessionFunctions
	 */
	public function __construct(SessionFunctions $sessionFunctions)
	{
		$this->sessionFunctions = $sessionFunctions;
	}

	/**
	 * Check if a (public) user has access to an album
	 * if 0 : album does not exists
	 * if 1 : access is granted
	 * if 2 : album is private
	 * if 3 : album is password protected and require user input.
	 *
	 * @param $albumID
	 * @param bool obeyHidden
	 *
	 * @return int
	 */
	public function album($albumID, bool $obeyHidden = false)
	{
		if (in_array($albumID, array(
			'f',
			's',
			'r',
			'0',
		))) {
			return 1; // access granted
		}

		$album = Album::find($albumID);
		if ($album == null) {
			return 0;  // Does not exist
		}

		if ($this->sessionFunctions->is_current_user($album->owner_id)) {
			return 1; // access granted
		}

		// Check if the album is shared with us
		if ($this->sessionFunctions->is_logged_in() &&
			$album->shared_with->map(function ($user) {
				return $user->id;
			})->contains($this->sessionFunctions->id())) {
			return 1; // access granted
		}

		if ($album->public != 1 ||
			($obeyHidden && $album->visible_hidden !== 1)) {
			return 2;  // Warning: Album private!
		}

		if ($album->password == '') {
			return 1;  // access granted
		}

		if ($this->sessionFunctions->has_visible_album($albumID)) {
			return 1;  // access granted
		}

		return 3;      // Please enter password first. // Warning: Wrong password!
	}

	/**
	 * Check if a (public) user has access to a picture.
	 *
	 * @param Photo $photo
	 *
	 * @return bool
	 */
	public function photo(Photo $photo)
	{
		if ($this->sessionFunctions->is_current_user($photo->owner_id)) {
			return true;
		}
		if ($photo->public === 1) {
			return true;
		}
		if ($this->album($photo->album_id) === 1) {
			return true;
		}

		return false;
	}
}
