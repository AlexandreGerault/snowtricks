<?php

declare(strict_types=1);

namespace App\UserInterface\Trick\Controller;

use App\UserInterface\Trick\DTO\RegisterNewTrickFormModel;
use App\UserInterface\Trick\Form\RegisterNewTrickFormType;
use App\UserInterface\Trick\ViewModel\HtmlRegisterNewTrickViewModel;
use Domain\Trick\Exception\TrickAlreadyExistsException;
use Domain\Trick\UseCase\RegisterNewTrick\RegisterNewTrick;
use Domain\Trick\UseCase\RegisterNewTrick\RegisterNewTrickPresenterInterface;
use Domain\Trick\UseCase\RegisterNewTrick\RegisterNewTrickRequest;
use Domain\Trick\UseCase\RegisterNewTrick\RegisterNewTrickResponse;
use Domain\Trick\ValueObject\File;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegisterNewTrickController extends AbstractController implements RegisterNewTrickPresenterInterface
{
    private string $redirectUrl;

    public function __construct(
        private HtmlRegisterNewTrickViewModel $vm,
        private RegisterNewTrick $useCase,
        private SluggerInterface $slugger,
        private AuthorizationCheckerInterface $authorizationChecker,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    #[Route(path: '/figures/ajouter', name: 'ajouter-une-nouvelle-figure')]
    public function __invoke(Request $request): RedirectResponse|Response
    {
        if (!$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }

        $registerNewTrickFormRequest = new RegisterNewTrickFormModel();
        $form = $this->createForm(RegisterNewTrickFormType::class, $registerNewTrickFormRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->useCase->executes(
                    $this->getRegisterNewTrickRequest(
                        $registerNewTrickFormRequest,
                        $this->prepareIllustrationsFormDomain($registerNewTrickFormRequest)
                    ),
                    $this
                );

                $this->addFlash("success", "Nouvelle figure ajoutée avec succès !");

                return $this->redirect($this->redirectUrl);
            } catch (InvalidArgumentException $e) {
                $this->vm->errors['TRICK_CANNOT_BE_CREATED'] = $e->getMessage();
            } catch (TrickAlreadyExistsException $e) {
                $this->vm->errors['TRICK_ALREADY_EXISTS'] = $e->getMessage();
            }
        }

        $this->vm->form = $form->createView();
        return $this->render('tricks/create.html.twig', ['vm' => $this->vm]);
    }

    public function presents(RegisterNewTrickResponse $response): void
    {
        $this->redirectUrl = $this->generateUrl('app_home');
    }

    private function generateFilename(
        RegisterNewTrickFormModel $registerNewTrickFormRequest,
        UploadedFile $file
    ): string {
        $orignalName = $registerNewTrickFormRequest->name;
        $originalExtension = $file->getClientOriginalExtension();
        $filename = $orignalName.uniqid();

        $filename = $this
            ->slugger
            ->slug($filename)
            ->lower()
            ->toString();

        $filename .= ".{$originalExtension}";

        return $filename;
    }

    private function prepareIllustrationsFormDomain(RegisterNewTrickFormModel $registerNewTrickFormRequest): array
    {
        return array_map(
            function (UploadedFile $file) use ($registerNewTrickFormRequest) {
                $filename = $this->generateFilename($registerNewTrickFormRequest, $file);

                return new File($filename, $file->getContent());
            },
            $registerNewTrickFormRequest->illustrations
        );
    }

    private function getRegisterNewTrickRequest(
        RegisterNewTrickFormModel $registerNewTrickFormRequest,
        array $illustrations
    ): RegisterNewTrickRequest {
        return new RegisterNewTrickRequest(
            thumbnail: new File(
                $this->generateFilename(
                    $registerNewTrickFormRequest,
                    $registerNewTrickFormRequest->thumbnail
                ),
                $registerNewTrickFormRequest->thumbnail->getContent()
            ),
            name: $registerNewTrickFormRequest->name,
            description: $registerNewTrickFormRequest->description,
            category: $registerNewTrickFormRequest->category->getName(),
            videosUrls: $registerNewTrickFormRequest->videos,
            illustrations: $illustrations
        );
    }
}
