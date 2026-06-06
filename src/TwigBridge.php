<?php

declare(strict_types=1);

namespace PhrameCMS\TwigBridge;

use PhrameCMS\Core\Contracts\TemplateRendererInterface;
use RuntimeException;

final class TwigBridge implements TemplateRendererInterface
{
    private const TWIG_ENVIRONMENT_CLASS = 'Twig\\Environment';
    private const TWIG_FILESYSTEM_LOADER_CLASS = 'Twig\\Loader\\FilesystemLoader';

    private object $twig;

    /**
     * @param array<int, string> $templatePaths
     */
    public function __construct(array $templatePaths = [], null|string|false $cacheDir = null)
    {
        if (!self::isAvailable()) {
            throw new RuntimeException('Twig is unavailable in this environment.');
        }

        if ($templatePaths === []) {
            $defaultPath = getcwd() . '/templates';
            $templatePaths = is_dir($defaultPath) ? [$defaultPath] : [];
        }

        $loaderClass = self::TWIG_FILESYSTEM_LOADER_CLASS;
        $loader = new $loaderClass($templatePaths);

        $cache = $cacheDir ?? false;

        $environmentClass = self::TWIG_ENVIRONMENT_CLASS;
        $this->twig = new $environmentClass($loader, [
            'cache' => $cache,
            'auto_reload' => true,
            'strict_variables' => false,
        ]);
    }

    public static function isAvailable(): bool
    {
        return class_exists(self::TWIG_ENVIRONMENT_CLASS)
            && class_exists(self::TWIG_FILESYSTEM_LOADER_CLASS);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function render(string $template, array $context = []): string
    {
        return $this->twig->render($template, $context);
    }
}
