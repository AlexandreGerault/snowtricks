<?php

declare(strict_types=1);

namespace Domain\Trick\UseCase\RegisterNewTrick;

use Domain\Tests\Trick\Factory\TrickFactory;
use Domain\Trick\Entity\Trick;
use Domain\Trick\Exception\TrickAlreadyExistsException;
use Domain\Trick\Gateway\IllustrationGateway;
use Domain\Trick\Gateway\TrickGateway;
use Domain\Trick\ValueObject\File;

class RegisterNewTrick
{
    public function __construct(private TrickGateway $tricksGateway, private IllustrationGateway $illustrationsGateway)
    {
    }

    /**
     * @throws TrickAlreadyExistsException
     */
    public function executes(RegisterNewTrickRequest $request, RegisterNewTrickPresenterInterface $presenter): void
    {
        if (!$this->tricksGateway->isNameAvailable($request->name)) {
            throw new TrickAlreadyExistsException('A trick with this name already exists');
        }

        $this->illustrationsGateway->store(
            "illustrations/{$request->name}",
            $request->thumbnail->filename,
            $request->thumbnail->content
        );

        foreach ($request->illustrations as $illustration) {
            $this->illustrationsGateway->store(
                "illustrations/{$request->name}",
                $illustration->filename,
                $illustration->content
            );
        }

        $trick = TrickFactory::new()
            ->name($request->name)
            ->illustrations(array_map(fn (File $illustration) => $illustration->filename, $request->illustrations))
            ->description($request->description)
            ->category($request->category)
            ->videos($request->videosUrls)
            ->thumbnail($request->thumbnail->filename)
            ->create();

        $this->tricksGateway->save($trick);

        $presenter->presents(new RegisterNewTrickResponse($trick));
    }
}
