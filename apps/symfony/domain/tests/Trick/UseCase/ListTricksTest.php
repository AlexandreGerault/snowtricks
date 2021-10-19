<?php

declare(strict_types=1);

namespace Domain\Tests\Trick\UseCase;

use Domain\Tests\Trick\Adapter\InMemoryTrickRepository;
use Domain\Trick\Entity\TrickOverview;
use Domain\Trick\UseCase\ListTricks\ListTricks;
use Domain\Trick\UseCase\ListTricks\ListTricksPresenterInterface;
use Domain\Trick\UseCase\ListTricks\ListTricksRequest;
use Domain\Trick\UseCase\ListTricks\ListTricksResponse;
use PHPUnit\Framework\TestCase;

class ListTricksTest extends TestCase implements ListTricksPresenterInterface
{
    private ListTricksResponse $response;
    private ListTricks $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $repository = new InMemoryTrickRepository([
            new TrickOverview(
                'Regular',
                'Flying',
                '/images/uploads/tricks/regular-thumbnail.jpg'
            ),
            new TrickOverview(
                'Indy',
                'Jump',
                '/images/uploads/tricks/indy-thumbnail.jpg'
            ),
        ]);
        $this->useCase = new ListTricks($repository);
    }

    public function testTheResponseHasAListOfAllTricks()
    {
        $request = new ListTricksRequest();
        $request->quantity = 2;

        $this->useCase->execute($request, $this);

        $this->assertCount(2, $this->response->tricks);
    }

    public function presents(ListTricksResponse $response): void
    {
        $this->response = $response;
    }
}
