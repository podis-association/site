<?php

require_once '../vendor/autoload.php';

// index.php
use JetBrains\PhpStorm\Pure;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

require __DIR__.'/../vendor/autoload.php';

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    #[Pure]
    public function registerBundles(): array
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new TwigBundle(),
        ];
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        // PHP equivalent of config/packages/framework.yaml
        $container->extension('framework', [
            'secret' => 'S0ME_SECRET'
        ]);

        $contents = require __DIR__.'/../translations/sitemap.php';

        $container->parameters()->set('sitemap', $contents);
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->add('home', ['/{lang}/home', '/home', '/'])->controller([$this, 'home']);
        $routes->add('about-us', '/{lang}/about-us')->controller([$this, 'aboutUs']);
        $routes->add('projects', '/{lang}/projects')->controller([$this, 'projects']);
        $routes->add('donate', '/{lang}/donate')->controller([$this, 'donate']);
        $routes->add('terms-of-use', '/{lang}/terms-of-use')->controller([$this, 'termsOfUse']);
    }

    public function home(string $lang = 'en'): Response
    {
        $parameter = $this->getContainer()->getParameter('sitemap');

        $context = [
            'menu' => $parameter[$lang]['menu'],
            'footer' => $parameter[$lang]['footer'],
        ];

        $content = $this->getContainer()->get('twig')->render("$lang/index.html.twig", $context);

        return new Response($content);
    }

    public function aboutUs(string $lang = 'en'): Response
    {
        $parameter = $this->getContainer()->getParameter('sitemap');

        $context = [
            'menu' => $parameter[$lang]['menu'],
            'footer' => $parameter[$lang]['footer'],
            'callToAction' => $parameter[$lang]['callToAction'],
        ];

        $obj = $this->getContainer()->get('twig');
        $content = $obj
            ->render("$lang/about-us.html.twig", $context);

        return new Response($content);
    }

    public function projects(string $lang = 'en'): Response
    {
        $parameter = $this->getContainer()->getParameter('sitemap');

        $context = [
            'menu' => $parameter[$lang]['menu'],
            'footer' => $parameter[$lang]['footer'],
            'callToAction' => $parameter[$lang]['callToAction'],
        ];

        $obj = $this->getContainer()->get('twig');
        $content = $obj
            ->render("$lang/projects.html.twig", $context);

        return new Response($content);
    }


    public function termsOfUse(string $lang = 'en'): Response
    {
        $parameter = $this->getContainer()->getParameter('sitemap');

        $context = [
            'menu' => $parameter[$lang]['menu'],
            'footer' => $parameter[$lang]['footer'],
        ];

        $obj = $this->getContainer()->get('twig');
        $content = $obj
            ->render("$lang/terms-of-use.html.twig", $context);

        return new Response($content);
    }

    public function donate(string $lang = 'en'): Response
    {
        $parameter = $this->getContainer()->getParameter('sitemap');

        $context = [
            'menu' => $parameter[$lang]['menu'],
            'footer' => $parameter[$lang]['footer'],
        ];

        $obj = $this->getContainer()->get('twig');
        $content = $obj
            ->render("$lang/donate.html.twig", $context);

        return new Response($content);
    }
}


$environment = getenv('APP_ENV') ?: 'prod';
$debug = (bool) getenv('APP_DEBUG') || false;

$kernel = new Kernel($environment, $debug);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);