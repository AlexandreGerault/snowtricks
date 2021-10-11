<?php

declare(strict_types=1);

namespace Domain\Tests\Tricks\UseCases;

use Domain\Tests\Tricks\Adapters\InMemoryTricksRepository;
use Domain\Tricks\Entity\Trick;
use Domain\Tricks\UseCases\ListTricks\ListTricks;
use Domain\Tricks\UseCases\ListTricks\ListTricksPresenterInterface;
use Domain\Tricks\UseCases\ListTricks\ListTricksRequest;
use Domain\Tricks\UseCases\ListTricks\ListTricksResponse;
use PHPUnit\Framework\TestCase;

class ListTricksTest extends TestCase implements ListTricksPresenterInterface
{
    private ListTricksResponse $response;
    private ListTricks $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $repository = new InMemoryTricksRepository([
            new Trick(name: "Regular", illustrations: ["/images/uploads/tricks/regular.jpg"], thumbnail: "/images/uploads/tricks/regular-thumbnail.jpg"),
            new Trick(name: "Indy", illustrations: ["/images/uploads/tricks/indy.jpg"], thumbnail: "/images/uploads/tricks/indy-thumbnail.jpg"),
        ]);
        $this->useCase = new ListTricks($repository);
    }

    public function test_the_response_has_a_list_of_all_tricks()
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
