<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixture;
use App\Repository\UserRepository;
use App\Tests\DataFixtures\FakerTrait;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class AbstractControllerTest extends WebTestCase
{
    use FakerTrait;
    use FixturesTrait;

    protected $url;

    private $client = null;

    private $accessToken;


    public function setUp():void
    {
        $this->client = static::createClient();
        $this->logIn();
    }

    private function logIn()
    {
        $this->loadFixtures([UserFixture::class]);
        $user = self::$container->get(UserRepository::class)->find(1);
        $jWTTokenManager = self::$container->get(JWTTokenManagerInterface::class);
        $this->accessToken = $jWTTokenManager->create($user);
    }

    /**
     * Sending request to controller
     *
     * @param string $method
     * @param string $uri
     * @param array  $data
     * @param array  $files
     * @param array  $server
     */
    protected function sendRequest(
        string $method,
        string $uri = null,
        array $data = [],
        array $files = [],
        array $server = []
    ) {
        #$this->client->disableReboot();
        #$this->client->enableProfiler();
        if (!empty($this->accessToken)) {
            $server = array_merge(
                $server,
                #['HTTP_Authorization' => 'Bearer'.' '.$this->accessToken]
                ['HTTP_Authorization' => $this->accessToken]
            );
        }
        $data['workspace'] = 'admin';
        $this->client->request($method, $this->url . $uri, $data, $files, $server);
    }

    /**
     * Get decoded result
     *
     * @return array
     */
    protected function getDecodedResult(): array
    {
        $result = $this->client->getResponse()->getContent();
        $result = json_decode($result, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            return [];
        }

        return $result;
    }

    /**
     * Make some basic common assertion for all tests
     *
     * @param array  $result
     * @param int    $responseCode
     * @param string $contentType
     */
    protected function basicAssertions(
        array $result,
        int $responseCode = Response::HTTP_OK,
        $contentType = 'application/json'
    ) {
        $this->assertTrue($this->client->getResponse()->headers->contains('content-type', $contentType));
        $this->assertEquals($responseCode, $this->client->getResponse()->getStatusCode());
    }
}