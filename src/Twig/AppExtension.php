<?php

namespace App\Twig;

use App\Entity\Career;
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
            new TwigFunction('set_main_image', [$this, 'setMainImage']),
            new TwigFunction('print_dates', [$this, 'printDates'])
        ];
    }

    public function setActiveRoute(string $route) : string
    {
        $class = "nav-item mx-0 mx-lg-1 rounded";
        $currentRoute = $this->requestStack->getCurrentRequest()->attributes->get('_route');

        return $currentRoute === $route ? $class . ' active' : $class;
    }

    public function setMainImage(string $currentImage, string $mainImage) : string
    {
        $class = "project-image-update mb-3";
        return $mainImage === $currentImage ? $class . " main-image" : $class;
    }

    public function printDates(Career $career)
    {
        $and = "";
        if ($career->getEndDate())
        {
            $interval = date_diff($career->getStartDate(), $career->getEndDate());

            $years = $interval->y ? $interval->y === 1 ? "1 an" : $interval->y . " ans" : "";
            $months = $interval->m ? $interval->m . " mois" : "";

            if ($years !== "" and $months !== "")
            {
                $and = " et ";
            }

            $intervalStr = " - " . $years . $and . $months;

            $dates = "Du <b>" . $career->getStartDate()->format('m/y') . "</b> au <b>" . $career->getEndDate()->format('m/y') . "</b><i>". $intervalStr . "</i>";
        }
        else
        {
            $dates = "Le <b>" . $career->getStartDate()->format('d/m/y') . "</b>";
        }

        return $dates;
    }
}
