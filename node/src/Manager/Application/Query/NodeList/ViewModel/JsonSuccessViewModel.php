<?php

namespace App\Manager\Application\Query\NodeList\ViewModel;

use App\Manager\Application\Query\NodeList\ViewModel\Dto\JsonNodeResponse;
use App\Manager\Application\Query\NodeList\ViewModel\Dto\JsonVirtualNodeResponse;
use App\Manager\Domain\Model\Aggregate\Node\Node;
use App\Manager\Domain\Model\Aggregate\Node\VirtualNode;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Attribute\Ignore;

#[OA\Schema(
    title: 'NodeListSuccessResponse',
)]
final class JsonSuccessViewModel extends JsonNodeListViewModel
{
    /**
     * @param array<Node> $nodesList
     */
    public function __construct(#[Ignore ] private readonly array $nodesList)
    {
        parent::__construct(Response::HTTP_OK);
    }

    /**
     * @return array<JsonNodeResponse>
     */
    public function getNodes(): array
    {
        return array_map($this->convertNodeForResponse(...), $this->nodesList);
    }

    private function convertNodeForResponse(Node $node): JsonNodeResponse
    {
        /** @var array<JsonVirtualNodeResponse> $virtualNodeResponseList */
        $virtualNodeResponseList = $node->getVirtualNodes()->map($this->convertVirtualNodeForResponse(...));

        return new JsonNodeResponse(
            $node->getStringId(),
            $node->getHost(),
            $node->getNetworkPort(),
            $node->getMembershipState(),
            $node->getJoinedAt(),
            $node->getWeight(),
            $node->isSeed(),
            $node->getLabel(),
            array_values($virtualNodeResponseList)
        );
    }

    private function convertVirtualNodeForResponse(VirtualNode $virtualNode): JsonVirtualNodeResponse
    {
        return new JsonVirtualNodeResponse(
            $virtualNode->getStringId(),
            $virtualNode->getSubLabel(),
            $virtualNode->getSlot(),
            $virtualNode->isActive(),
            $virtualNode->getCreatedAt()
        );
    }
}
