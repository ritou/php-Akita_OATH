<?php

require_once dirname(__FILE__) . '/../../src/Akita/OATH.php';

class Akita_OATH_Test extends PHPUnit_Framework_TestCase
{
    private $secret = "1234567890";
    private $counter = 1;
    private $manualtime = 1332083784;
    
    // test for oath->totp
    function test_totp()
    {
        $oath = new oath();
        $totp = $oath->totp($this->secret, $this->manualtime);
        $this->assertEquals('142045', $totp, 'Invalid TOTP');
        $totp = $oath->totp($this->secret);
        $this->assertNotEquals('142045', $totp, 'Invalid TOTP');
    }

    // test for oath->hotp
    function test_hotp()
    {
        $oath = new oath();
        $hotp = $oath->hotp($this->secret, $this->counter);
        $this->assertEquals('263420', $hotp, 'Invalid HOTP');
        $this->counter += 1;
        $hotp = $oath->hotp($this->secret, $this->counter);
        $this->assertEquals('092045', $hotp, 'Invalid HOTP');
    }

    // test for oath->validateTotp
    function test_validateTotp()
    {
        $oath = new oath();
        $totp = $oath->totp($this->secret, $this->manualtime);
        $this->assertEquals('142045', $totp, 'Invalid TOTP');
        $this->assertTrue($oath->validateTotp($totp, $this->secret, $this->manualtime), 'TOTP Validation Error');
        $current_totp = $oath->totp($this->secret);
        $this->assertFalse($oath->validateTotp($totp, $this->secret), 'TOTP Validation Error');
    }
}
