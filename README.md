Akita_OATH
======================================================
This is php OATH One Time Passwords Library.

OATH : http://www.openauthentication.org/
HOTP : http://tools.ietf.org/html/rfc4226
TOTP : http://tools.ietf.org/html/rfc6238

Usage
------------------------------------------------------
`    // raw secret(not Base32 encoded)
    $secret = "1234567890";
    $counter = 1;
    
    $oath = new oath();
    
    // Generate HOTP 
    $hotp = $oath->hotp($secret, $counter);
    
    // HOTP Validation
    $status = $oath->validateHotp($hotp, $secret, $counter);
    
    // Generate TOTP
    $totp = $oath->totp($secret);
    
    // TOTP Validation
    $status = $oath->validateTotp($totp, $secret);`

AUTHOR
------------------------------------------------------
@ritou ritou@gmail.com

LISENCE
------------------------------------------------------
MIT Lisense.
