<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\RequestHandlers\Api;

use FediE2EE\PKDServer\{
    Meta\Route,
    Traits\ReqTrait
};
use Override;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\{
    ServerRequestInterface,
    ResponseInterface
};

class Info implements RequestHandlerInterface
{
    use ReqTrait;

    #[Route("/api/info")]
    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $keys = $this->config()->getSigningKeys();
        return $this->json([
            '!pkd-context' => 'fedi-e2ee:v1/api/info',
            'current-time' => time(),
            'public-key' => $keys->publicKey->toString(),
        ]);
    }
}
