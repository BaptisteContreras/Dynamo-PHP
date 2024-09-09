<?php

namespace App\Tests\Application;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

abstract class AppTestCase extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    protected KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->client->followRedirects();

        /** @var DatabaseToolCollection $dbToolCollection */
        $dbToolCollection = static::getContainer()->get(DatabaseToolCollection::class);
        $this->databaseTool = $dbToolCollection->get();
    }

    protected function postJson(string $uri, array $data): Crawler
    {
        return $this->client->request('POST', $uri, server: [
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($data));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
        unset($this->client);
    }
}
