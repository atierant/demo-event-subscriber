<?php
declare(strict_types=1);

namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation à utiliser dans la classe de l'entité liée aux pays pour filtrer automatiquement dessus.
 * Voir l'entité Post pour un exemple d'utilisation.
 *
 * @Annotation
 * @Target("CLASS")
 */
final class CountryCheck
{
    public $fieldName;
}
