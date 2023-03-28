<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Annotation;

use App\Annotation\CountryCheck;
use PHPUnit\Framework\TestCase;

class CountryCheckTest extends TestCase
{
    /**
     * Ensures that annotations are created correctly.
     */
    public function testCreateCountryCheckAnnotation(): void
    {
        $countryCheck = new CountryCheck(CountryCheck::KEY);

        $this->assertIsObject($countryCheck);
    }
}
