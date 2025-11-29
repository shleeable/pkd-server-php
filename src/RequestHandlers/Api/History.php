<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\RequestHandlers\Api;

use DateTime;
use FediE2EE\PKD\Crypto\Exceptions\{
    JsonException,
    NotImplementedException
};
use FediE2EE\PKDServer\Exceptions\{
    CacheException,
    DependencyException,
    TableException
};
use FediE2EE\PKDServer\Tables\MerkleState;
use SodiumException;
use TypeError;
use FediE2EE\PKDServer\Meta\Route;
use FediE2EE\PKDServer\Traits\ReqTrait;
use Override;
use Psr\Http\Message\{
    ResponseInterface,
    ServerRequestInterface
};
use Psr\Http\Server\RequestHandlerInterface;

class History implements RequestHandlerInterface
{
    use ReqTrait;

    protected MerkleState $merkleState;

    /**
     * @throws DependencyException
     * @throws TableException
     * @throws CacheException
     */
    public function __construct()
    {
        $merkleState = $this->table('MerkleState');
        if (!($merkleState instanceof MerkleState)) {
            throw new TypeError('Could not load MerkleState table at runtime');
        }
        $this->merkleState = $merkleState;
    }

    /**
     * @throws DependencyException
     * @throws JsonException
     * @throws NotImplementedException
     * @throws SodiumException
     */
    #[Route("/api/history")]
    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $latest = $this->merkleState->getLatestRoot();
        $leaf = $this->merkleState->getLeafByRoot($latest);
        return $this->json([
            '!pkd-context' => 'fedi-e2ee:v1/api/history',
            'current-time' => (string) (new DateTime())->getTimestamp(),
            'created' => $leaf ? $leaf->created : '0',
            'merkle-root' => $latest,
        ]);
    }
}
