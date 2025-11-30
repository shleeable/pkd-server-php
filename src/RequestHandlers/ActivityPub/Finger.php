<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\RequestHandlers\ActivityPub;

use FediE2EE\PKDServer\{
    Meta\Route,
    Traits\ActivityStreamsTrait,
    Traits\ReqTrait
};
use Override;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\{
    ServerRequestInterface,
    ResponseInterface
};

class Finger implements RequestHandlerInterface
{
    use ActivityStreamsTrait;
    use ReqTrait;

    #[Route("/.well-known/webfinger")]
    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->json(['test']);
    }
}
