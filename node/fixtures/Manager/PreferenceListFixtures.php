<?php

namespace DataFixtures\Manager;

use App\Shared\Infrastructure\Persistence\Doctrine\PreferenceListEntry;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV7;

class PreferenceListFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $entry1 = new PreferenceListEntry(
            40,
            UuidV7::fromString('0191d76a-4888-7db6-accc-7d8fdc8d3cf8'),
            [UuidV7::fromString('0191d76a-f1ec-7981-a2c6-82d5e66c128d'), UuidV7::fromString('0191d76b-21a5-7298-ae1d-47884cfaaf2b')],
            [],
            UuidV7::fromString('0191d76a-c616-7b9a-8a26-a4e4de26d345')
        );
        $entry2 = new PreferenceListEntry(
            80,
            UuidV7::fromString('0191d76b-76e2-7c43-ad1d-a79c36b8e6ea'),
            [],
            [UuidV7::fromString('0191d76b-21a5-7298-ae1d-47884cfaaf2b')],
            UuidV7::fromString('0191d76b-88b6-7fe0-a589-25d0fa68e988')
        );

        $entry3 = new PreferenceListEntry(
            163,
            UuidV7::fromString('0191d76b-e577-7a70-9e99-7e81ab8a1251'),
            [UuidV7::fromString('0191d76c-50c1-75a6-a55f-223e2eae59c0'), UuidV7::fromString('0191d76c-608a-7d87-a2ea-7b04815255ab')],
            [UuidV7::fromString('0191d76c-0fc4-75bd-bd88-a45d7aee1efd'), UuidV7::fromString('0191d76c-2fc8-7305-b159-e485c5bdb59b')],
            UuidV7::fromString('0191d76b-fd55-740c-b696-11ea51ce69a1')
        );

        $manager->persist($entry1);
        $manager->persist($entry2);
        $manager->persist($entry3);
        $manager->flush();
    }
}
