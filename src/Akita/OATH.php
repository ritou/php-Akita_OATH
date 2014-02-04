<?php

/**
 * Akita_OATH
 *
 * OTP Utility class
 *
 * PHP versions 5
 *
 * LICENSE: MIT License
 *
 * @category  OATH
 * @package   Akita_OATH
 * @author    Ryo Ito <ritou.06@gmail.com>
 * @copyright 2012 Ryo Ito
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @link      http://
 */

/**
 * Akita_OATH
 *
 * OTP Utility class
 *
 * @category  OAuth2
 * @package   Akita_OATH
 * @author    Ryo Ito <ritou.06@gmail.com>
 * @copyright 2012 Ryo Ito
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @link      http://
 */
class oath
{
    private $_digits;
    private $_timestep;

    public function __construct($digits=6, $timestep=30){
        $this->_digits = $digits;
        $this->_timestep = $timestep;
    }

	public function totp($secret, $manualtime=null)
	{
        $counter = $this->getTimeCounter($manualtime);
		return $this->hotp($secret, $counter);
	}

	public function hotp($secret, $counter)
	{
		return str_pad(self::truncate(self::hash($secret, $counter), $this->_digits), $this->_digits, 0, STR_PAD_LEFT);
	}

    public function validateTotp($totp, $secret, $manualtime=null)
    {
        $valid_totp = $this->totp($secret, $manualtime);
        return ($totp === $valid_totp);
    }

    public function validateHotp($hotp, $secret, $counter)
    {
        $valid_hotp = $this->hotp($secret, $counter);
        return ($hotp === $valid_hotp);
    }

	private function getTimeCounter($manualtime=null)
	{
        	$calc_ts = (!is_null($manualtime)) ? $manualtime : time();
		return floor($calc_ts/$this->_timestep);
	}

	private static function hash($secret, $counter)
	{
        $bin_counter = pack('N*', 0) . pack('N*', $counter);
		$hash = hash_hmac ('sha1', $bin_counter, $secret, true);
		return $hash;
	}

	private static function truncate($hash, $length = 6)
	{
        $offset = ord($hash[19]) & 0xf;
		return
		(
			((ord($hash[$offset+0]) & 0x7f) << 24 ) |
			((ord($hash[$offset+1]) & 0xff) << 16 ) |
			((ord($hash[$offset+2]) & 0xff) << 8 ) |
			(ord($hash[$offset+3]) & 0xff)
		) % pow(10,$length);
	}
}
