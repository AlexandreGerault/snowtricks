<?php

declare(strict_types=1);

namespace Domain\Trick\UseCase\EditTrick;

use Domain\Trick\Gateway\TrickGateway;
use Symfony\Component\Uid\Uuid;

class EditTrick
{
    public function __construct(private readonly TrickGateway $trickGateway)
    {
    }

    public function execute(EditTrickRequest $request, EditTrickPresenterInterface $presenter): void
    {
        $trick = $this->trickGateway->findByUuid(Uuid::fromString($request->uuid));

        $newTrick = $trick
            ->rename($request->name)
            ->changeDescription($request->description)
            ->changeVideoLinks($request->videoLinks)
            ->attachToCategory($request->category)
        ;

        $this->trickGateway->update($trick);

        $presenter->presents(new EditTrickResponse($newTrick));
    }
}
