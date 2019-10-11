<?php
declare(strict_types=1);

namespace App\Filter;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use InvalidArgumentException;

/**
 * Class TreatmentSiteContractFilter
 *
 * Filtre les contrats par les sites de traitement fournis par le Configurator
 *
 * @see http://blog.mthomas.fr/2016/12/07/mettre-en-place-un-filter-avec-doctrine/
 */
class CountryFilter extends SQLFilter
{
    /** @var Reader $reader */
    protected $reader;

    /**
     * Gets the SQL query part to add to a query.
     *
     * @param ClassMetaData $targetEntity
     * @param string        $targetTableAlias
     *
     * @return string The constraint SQL if there is available, empty string otherwise.
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (empty($this->reader)) {
            return '';
        }

        // The Doctrine filter is called for any query on any entity
        // Check if the current entity is marked with an annotation "CountryCheck"
        $countryCheck = $this->reader->getClassAnnotation(
            $targetEntity->getReflectionClass(),
            'App\\Annotation\\CountryCheck'
        );

        if (!$countryCheck) {
            return '';
        }

        // FieldName parameter in annotation
        $fieldName = $countryCheck->fieldName;

        try {
            // Parameter name given in the subscriber
            $country = $this->getParameter('country');
//            $country = trim($country, "'");
        } catch (InvalidArgumentException $e) {
            // No treatment_site_id has been defined
            return '';
        }
        if (empty($fieldName) || empty($country)) {
            return '';
        }

        return sprintf('%s.%s = %s', $targetTableAlias, $fieldName, $country);
    }

    /**
     * @param Reader $reader
     */
    public function setAnnotationReader(Reader $reader)
    {
        $this->reader = $reader;
    }
}
