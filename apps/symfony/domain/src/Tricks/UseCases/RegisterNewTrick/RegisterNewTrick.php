<?php

declare(strict_types=1);

namespace Domain\Tricks\UseCases\RegisterNewTrick;

use Domain\Tricks\Entity\Trick;
use Domain\Tricks\Exceptions\TrickAlreadyExistsException;
use Domain\Tricks\Gateway\TricksGateway;

class RegisterNewTrick
{
    public function __construct(private TricksGateway $gateway)
    {
    }

    /**
     * @throws TrickAlreadyExistsException
     */
    public function executes(RegisterNewTrickRequest $request, RegisterNewTrickPresenterInterface $presenter): void
    {
        $trick = new Trick(
            name: $request->name,
            illustrations: $request->illustrations,
            description: $request->description,
            category: $request->category,
            videos: []
        );

        if ( ! $this->gateway->isNameAvailable($request->name)) {
            throw new TrickAlreadyExistsException("A trick with this name already exists");
        }

        $this->gateway->save($trick);

        $presenter->presents(new RegisterNewTrickResponse($trick));
    }
}
