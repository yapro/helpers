<?php

namespace YaPro\Helper\Validation;

/**
 * @param string $pattern
 * @return bool
 */
function isPatternValid($pattern)
{
    set_error_handler(function() {}, E_WARNING);
    $isValid = preg_match($pattern, '') !== false;
    restore_error_handler();
    return $isValid;
}