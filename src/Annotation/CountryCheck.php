<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation à utiliser dans la classe de l'entité liée aux pays pour filtrer automatiquement dessus.
 * Voir l'entité Post pour un exemple d'utilisation.
 *
 * @Annotation
 *
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::TARGET_FUNCTION)]
final class CountryCheck
{
    public const KEY = 'fieldName';

    public function __construct(public ?string $fieldName)
    {
    }
}
