<?php

declare(strict_types=1);

namespace App\Tests\Adapter;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

class DummyUrlGenerator implements UrlGeneratorInterface
{
    public function __construct(public array $routes)
    {
    }

    public function addRoute(string $name, string $path)
    {
        $this->routes[$name] = $path;
    }

    public function setContext(RequestContext $context)
    {
        // TODO: Implement setContext() method.
    }

    public function getContext()
    {
        // TODO: Implement getContext() method.
    }

    public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH)
    {
        return $this->routes['name'] ?? throw new \RuntimeException("Route not found");
    }
}
