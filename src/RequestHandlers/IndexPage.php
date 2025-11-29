<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\RequestHandlers;

use FediE2EE\PKDServer\{
    Meta\Route,
    ServerConfig,
    Traits\ReqTrait
};
use Override;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\{
    ServerRequestInterface,
    ResponseInterface
};

class IndexPage implements RequestHandlerInterface
{
    use ReqTrait;

    #[Route("/")]
    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!($this->config instanceof ServerConfig)) {
            throw new \Exception('not injected');
        }
        return $this->twig('index.twig');
    }
}
