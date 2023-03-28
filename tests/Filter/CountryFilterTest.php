<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Filter;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Filter\CountryFilter;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CountryFilterTest extends WebTestCase
{
    /**
     * Ensures that annotations are created correctly.
     */
    public function testCreateCountryFilter(): void
    {
        $client = static::createClient();
        /** @var Registry $registry */
        $registry = $client->getContainer()->get('doctrine');

        $manager = $registry->getManagerForClass(Post::class);
        $countryFilter = new CountryFilter($manager);
        $targetEntity = $manager->getClassMetadata(Post::class);
        $countryFilter->setParameter('country', 'fr');
        $countryFilter->addFilterConstraint($targetEntity, 'p');

        $manager = $registry->getManagerForClass(User::class);
        $countryFilter = new CountryFilter($manager);
        $targetEntity = $manager->getClassMetadata(User::class);
        $countryFilter->setParameter('country', 'fr');
        $countryFilter->addFilterConstraint($targetEntity, 'p');

        $manager = $registry->getManagerForClass(Comment::class);
        $countryFilter = new CountryFilter($manager);
        $targetEntity = $manager->getClassMetadata(Comment::class);
        $countryFilter->setParameter('country', 'fr');
        $countryFilter->addFilterConstraint($targetEntity, 'p');

        $this->assertIsObject($countryFilter);
    }
}
