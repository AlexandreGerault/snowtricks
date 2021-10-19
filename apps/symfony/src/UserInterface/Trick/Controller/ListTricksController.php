<?php

declare(strict_types=1);

namespace App\UserInterface\Trick\Controller;

use App\UserInterface\Trick\ViewModel\HtmlListTricksViewModel;
use Domain\Trick\UseCase\ListTricks\ListTricks;
use Domain\Trick\UseCase\ListTricks\ListTricksPresenterInterface;
use Domain\Trick\UseCase\ListTricks\ListTricksRequest;
use Domain\Trick\UseCase\ListTricks\ListTricksResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListTricksController extends AbstractController implements ListTricksPresenterInterface
{
    public function __construct(private HtmlListTricksViewModel $vm)
    {
    }

    #[Route('/', name: 'app_home')]
    public function __invoke(ListTricks $useCase): Response
    {
        $listTricksRequest = new ListTricksRequest();
        $listTricksRequest->quantity = 15;

        $useCase->execute($listTricksRequest, $this);

        return $this->render('tricks/index.html.twig', ['vm' => $this->vm]);
    }

    public function presents(ListTricksResponse $response): void
    {
        $this->vm->tricks = $response->tricks;
    }
}
