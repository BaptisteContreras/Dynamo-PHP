<?php

namespace App\Admin\Infrastructure\Symfony\Components;

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
}
