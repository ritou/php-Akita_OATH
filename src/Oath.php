<?php

namespace Oath;

/**
 * Class Oath
 *
 * @package Oath
 */
class Oath
{

	/**
	 * @var int
	 */
	private $digits;

	/**
	 * @var int
	 */
	private $timestep;

	/**
	 * Oath constructor.
	 *
	 * @param int $digits
	 * @param int $timestep
	 */
	public function __construct($digits = 6, $timestep = 30)
	{
		$this->digits = $digits;
		$this->timestep = $timestep;
	}

	/**
	 * @param string $secret
	 * @param int $manualtime
	 * @return string
	 */
	public function totp($secret, $manualtime = null)
	{
		$counter = $this->getTimeCounter($manualtime);
		return $this->hotp($secret, $counter);
	}

	/**
	 * @param string $secret
	 * @param int $counter
	 * @return string
	 */
	public function hotp($secret, $counter)
	{
		return str_pad(self::truncate(self::hash($secret, $counter), $this->digits), $this->digits, 0, STR_PAD_LEFT);
	}

	/**
	 * @param string $totp
	 * @param string $secret
	 * @param int $manualtime
	 * @return bool
	 */
	public function validateTotp($totp, $secret, $manualtime = null)
	{
		$valid_totp = $this->totp($secret, $manualtime);
		return ($totp === $valid_totp);
	}

	/**
	 * @param string $hotp
	 * @param string $secret
	 * @param int $counter
	 * @return bool
	 */
	public function validateHotp($hotp, $secret, $counter)
	{
		$valid_hotp = $this->hotp($secret, $counter);
		return ($hotp === $valid_hotp);
	}

	/**
	 * @param int $manualtime
	 * @return int
	 */
	private function getTimeCounter($manualtime = null)
	{
		$calc_ts = (!is_null($manualtime)) ? $manualtime : time();
		return floor($calc_ts / $this->timestep);
	}

	/**
	 * @param string $secret
	 * @param int $counter
	 * @return string
	 */
	private static function hash($secret, $counter)
	{
		$bin_counter = pack('N*', 0) . pack('N*', $counter);
		$hash = hash_hmac('sha1', $bin_counter, $secret, true);
		return $hash;
	}

	/**
	 * @param string $hash
	 * @param int $length
	 * @return int
	 */
	private static function truncate($hash, $length = 6)
	{
		$offset = ord($hash[19]) & 0xf;
		return
			(
				((ord($hash[$offset + 0]) & 0x7f) << 24) |
				((ord($hash[$offset + 1]) & 0xff) << 16) |
				((ord($hash[$offset + 2]) & 0xff) << 8) |
				(ord($hash[$offset + 3]) & 0xff)
			) % pow(10, $length);
	}

}
