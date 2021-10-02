<?php

declare(strict_types=1);

namespace Domain\Tricks\UseCases\ListTricks;

use Domain\Tricks\Gateway\TricksGateway;

class ListTricks
{
    public function __construct(private TricksGateway $tricksGateway)
    {
    }

    public function execute(ListTricksRequest $request, ListTricksPresenterInterface $presenter)
    {
        $tricks = $this->tricksGateway->getLastTricks($request->quantity);

        $response = new ListTricksResponse();
        $response->tricks = $tricks;

        $presenter->presents($response);
    }
}
