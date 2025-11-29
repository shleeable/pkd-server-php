<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\RequestHandlers\Api;

use FediE2EE\PKD\Crypto\Exceptions\NotImplementedException;
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

class ReplicaInfo implements RequestHandlerInterface
{
    use ReqTrait;

    #[Route("/api/replicas/{replica_id}")]
    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        throw new NotImplementedException('foo');
    }
}
