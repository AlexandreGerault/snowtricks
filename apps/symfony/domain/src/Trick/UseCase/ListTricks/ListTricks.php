<?php

declare(strict_types=1);

namespace Domain\Trick\UseCase\ListTricks;

use Domain\Trick\Gateway\TrickGateway;

class ListTricks
{
    public function __construct(private TrickGateway $tricksGateway)
    {
    }

    public function execute(ListTricksRequest $request, ListTricksPresenterInterface $presenter)
    {
        $tricks = $this->tricksGateway->getLastTricksOverviews($request->quantity);

        $response = new ListTricksResponse();
        $response->tricks = $tricks;

        $presenter->presents($response);
    }
}
