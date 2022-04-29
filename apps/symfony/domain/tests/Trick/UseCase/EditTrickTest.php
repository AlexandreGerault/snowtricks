<?php

declare(strict_types=1);

namespace Domain\Tests\Trick\UseCase;

use Domain\Tests\Trick\Adapter\InMemoryTrickRepository;
use Domain\Tests\Trick\Factory\TrickFactory;
use Domain\Trick\UseCase\EditTrick\EditTrick;
use Domain\Trick\UseCase\EditTrick\EditTrickPresenterInterface;
use Domain\Trick\UseCase\EditTrick\EditTrickRequest;
use Domain\Trick\UseCase\EditTrick\EditTrickResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class EditTrickTest extends TestCase implements EditTrickPresenterInterface
{
    private string $uuid = "a05b241b-8237-4e57-9675-4e4cb8b6d9af";

    private EditTrickResponse $response;

    private EditTrick $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $oldTrick = TrickFactory::new()
            ->uuid(Uuid::fromString($this->uuid))
            ->name('oldTrick')
            ->category('oldCategory')
            ->description('oldDescription')
            ->create();
        $inMemoryTrickRepository = new InMemoryTrickRepository([$oldTrick]);

        $this->useCase = new EditTrick($inMemoryTrickRepository);
    }

    public function test_it_can_rename_a_trick(): void
    {
        $request = new EditTrickRequest($this->uuid, "New name", "Old description");

        $this->useCase->executes($request, $this);

        $this->assertEquals("New name", $this->response->trick->getName());
    }

    public function test_it_can_change_the_description(): void
    {
        $request = new EditTrickRequest($this->uuid, "Old name", "New description");

        $this->useCase->executes($request, $this);

        $this->assertEquals("New description", $this->response->trick->getDescription());
    }

    public function presents(EditTrickResponse $response): void
    {
        $this->response = $response;
    }
}
