<?php

namespace App\Admin\Infrastructure\Symfony\Components;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Menu
{
    public array $items = [
        [
            'label' => 'Overview',
            'route' => 'admin_pannel_index'
        ],
        [
            'label' => 'Topology',
            'route' => 'admin_pannel_topology'
        ],
        [
            'label' => 'Ring',
            'route' => 'admin_pannel_index'
        ],
    ];
    
    public function __construct(private RequestStack $requestStack)
    {
    }
    
    public function getCurrentRoute(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();
        return $request?->attributes->get('_route');
    }
    
    public function isActive(string $route): bool
    {
        return $this->getCurrentRoute() === $route;
    }
}
