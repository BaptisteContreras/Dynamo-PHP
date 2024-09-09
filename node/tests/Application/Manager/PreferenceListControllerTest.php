<?php

namespace App\Tests\Application\Manager;

use App\Tests\Application\AppTestCase;
use DataFixtures\Manager\PreferenceListFixtures;

class PreferenceListControllerTest extends AppTestCase
{
    public function testGetPreferenceListSuccess(): void
    {
        $this->databaseTool->loadFixtures([
            PreferenceListFixtures::class,
        ]);

        $this->client->request('GET', 'manager/preference-list');

        self::assertResponseIsSuccessful();
        self::assertJsonStringEqualsJsonFile(sprintf('%s/%s.json', __DIR__, __FUNCTION__), $this->client->getResponse()->getContent());
    }
}
