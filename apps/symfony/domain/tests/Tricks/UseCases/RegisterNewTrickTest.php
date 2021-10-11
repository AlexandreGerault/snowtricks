<?php

declare(strict_types=1);

namespace Domain\Tests\Tricks\UseCases;

use Domain\Tests\Tricks\Adapters\InMemoryTricksRepository;
use Domain\Tricks\Entity\Trick;
use Domain\Tricks\Exceptions\TrickAlreadyExistsException;
use Domain\Tricks\Gateway\IllustrationsGateway;
use Domain\Tricks\Gateway\TricksGateway;
use Domain\Tricks\UseCases\RegisterNewTrick\RegisterNewTrick;
use Domain\Tricks\UseCases\RegisterNewTrick\RegisterNewTrickPresenterInterface;
use Domain\Tricks\UseCases\RegisterNewTrick\RegisterNewTrickRequest;
use Domain\Tricks\UseCases\RegisterNewTrick\RegisterNewTrickResponse;
use Domain\Tricks\ValueObject\File;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RegisterNewTrickTest extends TestCase implements RegisterNewTrickPresenterInterface
{
    private TricksGateway $tricksGateway;
    private RegisterNewTrickResponse $response;
    private RegisterNewTrick $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tricksGateway = new InMemoryTricksRepository([
            new Trick(
                name: "Regular",
                illustrations: ["/images/uploads/tricks/regular.jpg"],
                thumbnail: "/images/uploads/tricks/indy.jpg"
            ),
            new Trick(
                name: "Indy",
                illustrations: ["/images/uploads/tricks/indy.jpg"],
                thumbnail: "/images/uploads/tricks/indy.jpg"
            ),
        ]);
        $illustrationGateway = new class implements IllustrationsGateway {
            public function store(string $path, string $filename, string $content): void
            {
            }
        };
        $this->useCase = new RegisterNewTrick($this->tricksGateway, $illustrationGateway);
    }

    /**
     * @throws TrickAlreadyExistsException
     */
    public function test_it_creates_the_trick(): void
    {
        $request = new RegisterNewTrickRequest(
            name: "MyTrick",
            description: "Ma description",
            category: "Aucune idée de quoi mettre",
            videosUrls: [],
            illustrations: [new File("/images/uploads/tricks/my-trick.jpg", '')],
            thumbnail: new File("/images/uploads/tricks/my-trick-2.png", '')
        );

        $this->useCase->executes($request, $this);

        $this->assertInstanceOf(Trick::class, $this->response->trick);
        $this->assertEquals("MyTrick", $this->response->trick->getName());
        $this->assertEquals("MyTrick", $this->tricksGateway->getTrickByName("MyTrick")->getName());
    }

    public function test_it_cannot_register_a_new_trick_if_name_already_exists(): void
    {
        $this->expectException(TrickAlreadyExistsException::class);

        $request = new RegisterNewTrickRequest(
            name: "Regular",
            description: "Ma description",
            category: "Aucune idée de quoi mettre",
            videosUrls: [],
            illustrations: ["/images/uploads/tricks/my-trick.jpg"],
            thumbnail: new File("/images/uploads/tricks/my-trick-2.png", '')
        );

        $this->useCase->executes($request, $this);
    }

    /**
     * @dataProvider provideInvalidDataForTrick
     */
    public function test_it_does_not_save_the_trick_if_name_is_empty(
        string $name,
        string $description,
        string $category,
        array $illustrations,
        File $thumbnail
    ) {
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

    public function presents(RegisterNewTrickResponse $response): void
    {
        $this->response = $response;
    }

    public function provideInvalidDataForTrick(): Generator
    {
        yield ["", "Description", "category", [new File("http://image", "")], new File("/images/uploads/tricks/my-trick-2.png", '')];
        yield ["Name", "", "category", [new File("http://image", "")], new File("/images/uploads/tricks/my-trick-2.png", '')];
        yield ["Name", "Description", "", [new File("http://image", "")], new File("/images/uploads/tricks/my-trick-2.png", '')];
        yield ["Name", "Description", "Category", [], new File("/images/uploads/tricks/my-trick-2.png", '')];
        yield ["Name", "Description", "Category", [new File("http://image", "")], new File("", "")];
    }
}
