<?php

namespace App\Manager\Application\Command\Join\ViewModel;

use App\Manager\Domain\Model\Aggregate\Node\Node;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Ignore;

#[OA\Schema(
    title: 'NodeJoinSuccessResponse',
)]
class JsonSuccessViewModel extends JsonJoinViewModel
{
    public function __construct(#[Ignore] private readonly Node $node)
    {
        parent::__construct(Response::HTTP_CREATED);
    }

    #[OA\Property(
        description: 'The unique UID of this node in the ring',
        example: '018f3554-c788-7a58-a441-89421daba28b'
    )]
    public function getId(): string
    {
        return $this->node->getId()->toRfc4122();
    }
}
