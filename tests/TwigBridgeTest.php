<?php

declare(strict_types=1);

namespace PhrameCMS\TwigBridge\Tests;

use PHPUnit\Framework\TestCase;
use PhrameCMS\TwigBridge\TwigBridge;

final class TwigBridgeTest extends TestCase
{
    public function testRenderRendersTemplate(): void
    {
        if (!TwigBridge::isAvailable()) {
            self::markTestSkipped('Twig is unavailable in this environment.');
        }

        $templatesDir = __DIR__ . '/fixtures/templates';
        $bridge = new TwigBridge([$templatesDir], false);

        $rendered = $bridge->render('welcome.html.twig', ['name' => 'PhrameCMS']);

        self::assertSame('<h1>Hello PhrameCMS</h1>', trim($rendered));
    }
}
