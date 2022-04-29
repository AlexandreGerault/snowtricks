<?php

declare(strict_types=1);

namespace Domain\Trick\UseCase\EditTrick;

use Domain\Tests\Trick\Factory\TrickFactory;
use Domain\Trick\Entity\Trick;
use Domain\Trick\Gateway\TrickGateway;
use Symfony\Component\Uid\Uuid;

class EditTrick
{
    public function __construct(private TrickGateway $trickGateway)
    {
    }

    public function executes(EditTrickRequest $request, EditTrickPresenterInterface $presenter)
    {
        $trick = $this->trickGateway->findByUuid(Uuid::fromString($request->uuid));

        $newTrick = new Trick(
            $trick->getId(),
            $request->name,
            $trick->getIllustrationsPath(),
            $request->description,
            $trick->getCategory(),
            $trick->getVideoLinks(),
            $trick->getThumbnailUrl()
        );

        $presenter->presents(new EditTrickResponse($newTrick));
    }
}
