<?php

declare(strict_types=1);

namespace Domain\Tests\Trick\UseCase;

use Domain\Tests\Trick\Adapter\DummyIllustrationGateway;
use Domain\Tests\Trick\Adapter\InMemoryTrickRepository;
use Domain\Tests\Trick\Factory\TrickFactory;
use Domain\Trick\Entity\Trick;
use Domain\Trick\Exception\TrickAlreadyExistsException;
use Domain\Trick\Gateway\TrickGateway;
use Domain\Trick\UseCase\RegisterNewTrick\RegisterNewTrick;
use Domain\Trick\UseCase\RegisterNewTrick\RegisterNewTrickPresenterInterface;
use Domain\Trick\UseCase\RegisterNewTrick\RegisterNewTrickRequest;
use Domain\Trick\UseCase\RegisterNewTrick\RegisterNewTrickResponse;
use Domain\Trick\ValueObject\File;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RegisterNewTrickTest extends TestCase implements RegisterNewTrickPresenterInterface
{
    private TrickGateway $tricksGateway;
    private RegisterNewTrickResponse $response;
    private RegisterNewTrick $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tricksGateway = new InMemoryTrickRepository([
            TrickFactory::new()->name('Regular')->create(),
            TrickFactory::new()->name('Indy')->create(),
        ]);
        $illustrationGateway = new DummyIllustrationGateway();
        $this->useCase = new RegisterNewTrick($this->tricksGateway, $illustrationGateway);
    }

    /**
     * @throws TrickAlreadyExistsException
     */
    public function testItCreatesTheTrick(): void
    {
        $request = new RegisterNewTrickRequest(
            thumbnail: new File('/images/uploads/tricks/my-trick-2.png', ''),
            name: 'MyTrick',
            description: 'Ma description',
            category: 'Aucune idée de quoi mettre',
            videosUrls: [],
            illustrations: [new File('/images/uploads/tricks/my-trick.jpg', '')]
        );

        $this->useCase->executes($request, $this);

        $this->assertInstanceOf(Trick::class, $this->response->trick);
        $this->assertEquals('MyTrick', $this->response->trick->getName());
        $this->assertEquals('MyTrick', $this->tricksGateway->getTrickByName('MyTrick')->getName());
    }

    public function testItCannotRegisterANewTrickIfNameAlreadyExists(): void
    {
        $this->expectException(TrickAlreadyExistsException::class);

        $request = new RegisterNewTrickRequest(
            thumbnail: new File('/images/uploads/tricks/my-trick-2.png', ''),
            name: 'Regular',
            description: 'Ma description',
            category: 'Aucune idée de quoi mettre',
            videosUrls: [],
            illustrations: ['/images/uploads/tricks/my-trick.jpg']
        );

        $this->useCase->executes($request, $this);
    }

    /**
     * @dataProvider provideInvalidDataForTrick
     *
     * @throws TrickAlreadyExistsException
     */
    public function testItDoesNotSaveTheTrickIfNameIsEmpty(
        string $name,
        string $description,
        string $category,
        array $illustrations,
        File $thumbnail
    ): void {
        $this->expectException(InvalidArgumentException::class);
        $request = new RegisterNewTrickRequest(
            thumbnail: $thumbnail,
            name: $name,
            description: $description,
            category: $category,
            videosUrls: [],
            illustrations: $illustrations
        );

        $this->useCase->executes($request, $this);
    }

    public function provideInvalidDataForTrick(): Generator
    {
        yield ['', 'Description', 'category', [new File('http://image', '')], new File('/images/uploads/tricks/my-trick-2.png', '')];
        yield ['Name', '', 'category', [new File('http://image', '')], new File('/images/uploads/tricks/my-trick-2.png', '')];
        yield ['Name', 'Description', '', [new File('http://image', '')], new File('/images/uploads/tricks/my-trick-2.png', '')];
        yield ['Name', 'Description', 'Category', [], new File('/images/uploads/tricks/my-trick-2.png', '')];
        yield ['Name', 'Description', 'Category', [new File('http://image', '')], new File('', '')];
    }

    public function presents(RegisterNewTrickResponse $response): void
    {
        $this->response = $response;
    }
}
