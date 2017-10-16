<?php

namespace Shield\Facebook\Test\Unit;

use PHPUnit\Framework\Assert;
use Shield\Shield\Contracts\Service;
use Shield\Testing\TestCase;
use Shield\FacebookMessenger\FacebookMessenger;

/**
 * Class ServiceTest
 *
 * @package \Shield\FacebookMessenger\Test\Unit
 */
class ServiceTest extends TestCase
{
    /**
     * @var \Shield\FacebookMessenger\FacebookMessenger
     */
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new FacebookMessenger;
    }


    /** @test */
    public function it_can_verify_a_valid_request()
    {
         $secret = 'raNd0mk3y';

        $this->app['config']['shield.services.facebook-messenger.options.app_secret'] = $secret;

        $content = 'sample content';

        $request = $this->request($content);

        $headers = [
            'X-Hub-Signature' => 'sha1=' . hash_hmac('sha1', $content, $secret)
        ];

        $request->headers->add($headers);

        Assert::assertTrue($this->service->verify($request, collect($this->app['config']['shield.services.facebook-messenger.options'])));
    }

    /** @test */
    public function it_will_not_verify_a_bad_request()
    {
        $this->app['config']['shield.services.github.token'] = 'good';

        $content = 'sample content';

        $request = $this->request($content);

        $headers = [
            'X-Hub-Signature' => 'sha1=' . hash_hmac('sha1', $content, 'bad')
        ];

        $request->headers->add($headers);

        Assert::assertFalse($this->service->verify($request, collect($this->app['config']['shield.services.facebook-messenger.options'])));
    }

    /** @test */
    public function it_has_correct_headers_required()
    {
        Assert::assertArraySubset(['X-Hub-Signature'], $this->service->headers());
    }
}
