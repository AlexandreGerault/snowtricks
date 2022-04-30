<?php

declare(strict_types=1);

namespace App\UserInterface\Trick\Controller;

use App\Infrastructure\Security\Entity\User;
use App\Infrastructure\Trick\Entity\Comment;
use App\Infrastructure\Trick\Entity\Trick;
use App\UserInterface\Trick\DTO\CommentTrickFormModel;
use App\UserInterface\Trick\Form\CommentTrickType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ShowTrickController extends AbstractController
{
    public function __construct(private EntityManagerInterface $_em)
    {
    }

    #[Route(path: '/figures/{name}', name: 'afficher-une-figure')]
    public function __invoke(Trick $trick, Request $request)
    {
        $commentTrickFormRequest = new CommentTrickFormModel();
        $form = $this->createForm(CommentTrickType::class, $commentTrickFormRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CommentTrickFormModel $data */
            $data = $form->getData();

            $comment = new Comment();
            $comment->setTrick($trick);
            $comment->setContent($data->content);
            $comment->setAuthor($this->getUser());

            $this->_em->persist($comment);
            $this->_em->flush();

            $this->redirect($request->headers->get("referer"));
        }

        return $this->render('tricks/show.html.twig', ['trick' => $trick, 'form' => $form->createView()]);
    }
}
