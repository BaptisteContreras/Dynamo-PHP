<?php

namespace App\Tests\Application;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AppTestCase extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    protected KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();

        /** @var DatabaseToolCollection $dbToolCollection */
        $dbToolCollection = static::getContainer()->get(DatabaseToolCollection::class);
        $this->databaseTool = $dbToolCollection->get();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
