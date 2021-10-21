<?php

declare(strict_types=1);

namespace App\Tests\Unit\Trick;

use App\Tests\Adapter\DummyAuthorizationChecker;
use App\Tests\Adapter\DummyUrlGenerator;
use App\UserInterface\Trick\Controller\RegisterNewTrickController;
use App\UserInterface\Trick\ViewModel\HtmlRegisterNewTrickViewModel;
use Domain\Tests\Trick\Adapter\DummyIllustrationGateway;
use Domain\Tests\Trick\Adapter\InMemoryTrickRepository;
use Domain\Trick\UseCase\RegisterNewTrick\RegisterNewTrick;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\AsciiSlugger;

class RegisterNewTrickControllerTest extends TestCase
{
    public function testItRedirectsAUserThatIsNotLoggedInToTheLoginPage(): void
    {
        $controller = $this->getController();

        $request = Request::create('/figures/ajouter');
        $response = ($controller)($request);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
    }

    public function testItShowsAUserTheForm(): void
    {
        $controller = $this->getController(true);

        $request = Request::create('/figures/ajouter');
        $response = ($controller)($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    private function getController(bool $accessGranted = false): RegisterNewTrickController
    {
        return new RegisterNewTrickController(
            new HtmlRegisterNewTrickViewModel(),
            new RegisterNewTrick(new InMemoryTrickRepository([]), new DummyIllustrationGateway()),
            new AsciiSlugger(),
            new DummyAuthorizationChecker($accessGranted),
            new DummyUrlGenerator(['app_login' => '/login'])
        );
    }
}
