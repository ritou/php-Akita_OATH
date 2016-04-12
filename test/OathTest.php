<?php

namespace Oath;

/**
 * Class OathTest
 *
 * @package Oath
 */
class OathTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var string
	 */
	private $secret = "1234567890";

	/**
	 * @var int
	 */
	private $counter = 1;

	/**
	 * @var int
	 */
	private $manualtime = 1332083784;

	/**
	 * @return void
	 */
	function testTotp()
	{
		$oath = new Oath();
		$totp = $oath->totp($this->secret, $this->manualtime);
		$this->assertEquals('142045', $totp, 'Invalid TOTP');
		$totp = $oath->totp($this->secret);
		$this->assertNotEquals('142045', $totp, 'Invalid TOTP');
	}

	/**
	 * @return void
	 */
	function testHotp()
	{
		$oath = new Oath();
		$hotp = $oath->hotp($this->secret, $this->counter);
		$this->assertEquals('263420', $hotp, 'Invalid HOTP');
		$this->counter += 1;
		$hotp = $oath->hotp($this->secret, $this->counter);
		$this->assertEquals('092045', $hotp, 'Invalid HOTP');
	}

	/**
	 * @return void
	 */
	function testValidateTotp()
	{
		$oath = new Oath();
		$totp = $oath->totp($this->secret, $this->manualtime);
		$this->assertEquals('142045', $totp, 'Invalid TOTP');
		$this->assertTrue($oath->validateTotp($totp, $this->secret, $this->manualtime), 'TOTP Validation Error');
		$this->assertTrue($oath->validateTotp('142045', $this->secret, $this->manualtime), 'TOTP Validation Error');
		$this->assertFalse($oath->validateTotp('0142045', $this->secret, $this->manualtime), 'TOTP Validation Error');
		$this->assertFalse($oath->validateTotp($totp, $this->secret), 'TOTP Validation Error');
	}

}
