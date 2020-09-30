<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('set_active_route', [$this, 'setActiveRoute']),
            new TwigFunction('set_main_image', [$this, 'setMainImage'])
        ];
    }

    public function setActiveRoute(array $routes) : string
    {
        $class = "nav-item mx-0 mx-lg-1 rounded";
        $currentRoute = $this->requestStack->getCurrentRequest()->attributes->get('_route');

        foreach ($routes as $route) {
            if ($currentRoute === $route) {
                return $class . ' active';
            }
        }
        return $class;
    }

    public function setMainImage(string $currentImage, string $mainImage, string $action) : string
    {
        $class = "";
        if ($action === "show")
        {
            $class = "project-image-show mb-3";
        }
        else if ($action === "update")
        {
            $class = "project-image-update mb-3";
        }
        return $mainImage === $currentImage ? $class . " main-image" : $class . " secondary-image";
    }
}
