<?php

declare(strict_types=1);

namespace Domain\Tricks\UseCases\RegisterNewTrick;

use Domain\Tricks\Entity\Trick;
use Domain\Tricks\Exceptions\TrickAlreadyExistsException;
use Domain\Tricks\Gateway\IllustrationsGateway;
use Domain\Tricks\Gateway\TricksGateway;
use Domain\Tricks\ValueObject\File;

class RegisterNewTrick
{
    public function __construct(private TricksGateway $tricksGateway, private IllustrationsGateway $illustrationsGateway)
    {
    }

    /**
     * @throws TrickAlreadyExistsException
     */
    public function executes(RegisterNewTrickRequest $request, RegisterNewTrickPresenterInterface $presenter): void
    {
        if (! $this->tricksGateway->isNameAvailable($request->name)) {
            throw new TrickAlreadyExistsException("A trick with this name already exists");
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

        $trick = new Trick(
            name: $request->name,
            illustrations: array_map(fn (File $illustration) => $illustration->filename, $request->illustrations),
            description: $request->description,
            category: $request->category,
            videos: $request->videosUrls,
            thumbnail: $request->thumbnail->filename
        );

        $this->tricksGateway->save($trick);

        $presenter->presents(new RegisterNewTrickResponse($trick));
    }
}
