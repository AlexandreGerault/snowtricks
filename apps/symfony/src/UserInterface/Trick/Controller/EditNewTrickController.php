<?php

declare(strict_types=1);

namespace App\UserInterface\Trick\Controller;

use App\Infrastructure\Trick\Entity\Trick;
use App\UserInterface\Trick\DTO\EditTrickFormModel;
use App\UserInterface\Trick\Form\EditTrickFormType;
use App\UserInterface\Trick\ViewModel\HtmlEditTrickViewModel;
use Domain\Trick\UseCase\EditTrick\EditTrick;
use Domain\Trick\UseCase\EditTrick\EditTrickPresenterInterface;
use Domain\Trick\UseCase\EditTrick\EditTrickRequest;
use Domain\Trick\UseCase\EditTrick\EditTrickResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class EditNewTrickController extends AbstractController implements EditTrickPresenterInterface
{
    private string $redirectUrl;

    public function __construct(
        private readonly EditTrick $useCase,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly UrlGeneratorInterface $urlGenerator,
        private HtmlEditTrickViewModel $vm
    ) {
    }

    #[Route(path: '/figures/{name}/edit', name: 'modifier-une-figure')]
    public function __invoke(Trick $trick, Request $request): RedirectResponse|Response
    {
        if (!$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }

        $editTrickFormRequest = new EditTrickFormModel($trick);
        $form = $this->createForm(EditTrickFormType::class, $editTrickFormRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EditTrickFormModel $data */
            $data = $form->getData();
            $this->useCase->execute(
                new EditTrickRequest(
                    $trick->getUuid()->toRfc4122(),
                    $data->name,
                    $data->description,
                    $data->videos,
                    $data->category->getName()
                ),
                $this
            );

            return $this->redirect($this->redirectUrl);
        }

        $this->vm->form = $form->createView();
        return $this->render('tricks/edit.html.twig', ['vm' => $this->vm]);
    }

    public function presents(EditTrickResponse $response): void
    {
        $this->redirectUrl = $this->generateUrl('app_home');
    }
}
