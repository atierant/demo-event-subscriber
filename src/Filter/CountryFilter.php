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

namespace App\Filter;

use App\Annotation\CountryCheck;
use App\Contract\CountryAware;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Class TreatmentSiteContractFilter.
 *
 * Filtre les posts par langue fournie par le Configurator
 *
 * @see http://blog.mthomas.fr/2016/12/07/mettre-en-place-un-filter-avec-doctrine/
 */
class CountryFilter extends SQLFilter
{
    /**
     * Gets the SQL query part to add to a query.
     *
     * @param string $targetTableAlias
     *
     * @return string the constraint SQL if there is available, empty string otherwise
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        // The Doctrine filter is called for any query on any entity

        $filter = '';

        if (!$targetEntity->reflClass) {
            return $filter;
        }

        // Check if the entity implements the LocalAware interface
        if (!$targetEntity->reflClass->implementsInterface(CountryAware::class)) {
            return $filter;
        }

        // Check if the current entity is marked with an annotation/attribute "CountryCheck"
        $attributes = $targetEntity->reflClass->getAttributes(CountryCheck::class);
        if (empty($attributes)) {
            return $filter;
        }

        // Normally, we only have one attribute of this kind on an entity, but maybe we can have multiple ones....
        // We will only process the first one
        foreach ($attributes as $attribute) {
            // Be sure we have the annotation on the entity
            $attributeName = $attribute->getName();
            if (!$this->supports($attributeName)) {
                continue;
            }

            // Be sure the required annotation on the entity have the correct value (= the field on which we filter)
            $arguments = $attribute->getArguments();
            if (empty($arguments)) {
                continue;
            }
            if (!\array_key_exists(CountryCheck::KEY, $arguments)) {
                continue;
            }

            // Récupération de l'instance de attribut
            $countryCheck = $attribute->newInstance();

            // FieldName parameter in annotation
            $fieldName = $countryCheck->fieldName;

            $filter .= sprintf(' %s.%s = %s', $targetTableAlias, $fieldName, $this->getParameter('country'));
        }

        return $filter;
    }

    private function supports(string $attributeName): bool
    {
        return CountryCheck::class === $attributeName;
    }
}
