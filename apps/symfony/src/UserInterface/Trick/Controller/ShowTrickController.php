<?php

declare(strict_types=1);

namespace App\UserInterface\Trick\Controller;

use App\Infrastructure\Trick\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ShowTrickController extends AbstractController
{
    #[Route(path: '/figures/{name}', name: 'afficher-une-figure')]
    public function __invoke(Trick $trick)
    {
        return $this->render('tricks/show.html.twig', ['trick' => $trick]);
    }
}
