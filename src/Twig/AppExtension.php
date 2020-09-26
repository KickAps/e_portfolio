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
            new TwigFunction('pluralize', [$this, 'pluralize']),
            new TwigFunction('set_active_route', [$this, 'setActiveRoute'])
        ];
    }

    public function pluralize(int $count, string $singular, string $plural = null) : string
    {
        $plural ??= $singular . "s";
        $s = $count <= 1 ? $singular : $plural;
        return "$count $s";
    }

    public function setActiveRoute(string $route) : string
    {
        $currentRoute = $this->requestStack->getCurrentRequest()->attributes->get('_route');
        return $currentRoute == $route ? 'nav-item active' : 'nav-item';
    }
}
