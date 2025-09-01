<?php

namespace App\Admin\Infrastructure\Symfony\Controller\Pannel;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/pannel', name: 'admin_pannel_')]
class PannelController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function index(): Response
    {
       return $this->render('@admin/index.html.twig', []);
    }
}
