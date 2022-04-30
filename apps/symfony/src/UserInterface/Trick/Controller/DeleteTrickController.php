<?php

declare(strict_types=1);

namespace App\UserInterface\Trick\Controller;

use App\Infrastructure\Trick\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DeleteTrickController extends AbstractController
{
    public function __construct(private EntityManagerInterface $_em)
    {
    }

    #[Route(path: '/figures/{name}/delete', name: 'supprimer-une-figure')]
    public function __invoke(Trick $trick, Request $request): RedirectResponse
    {
        $this->_em->remove($trick);
        $this->_em->flush();

        $this->addFlash("success", "La figure a bien été supprimée");

        return $this->redirect($request->headers->get('referer'));
    }
}
