<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ComposerJsonTest extends TestCase
{
    /**
     * Ensure that composer.json is valid JSON and contains essential keys.
     */
    public function test_composer_json_structure()
    {
        $path = dirname(__DIR__, 2) . '/composer.json';
        $this->assertFileExists($path);

        $content = file_get_contents($path);
        $this->assertNotFalse($content, 'Unable to read composer.json');

        $data = json_decode($content, true);
        $this->assertIsArray($data, 'composer.json did not decode to array');
        $this->assertArrayHasKey('name', $data);
        $this->assertSame('itinerary/planner', $data['name']);
        $this->assertArrayHasKey('require', $data);
        $this->assertArrayHasKey('php', $data['require']);
        $this->assertArrayHasKey('laravel/framework', $data['require']);
        $this->assertArrayHasKey('scripts', $data);
        $this->assertArrayHasKey('test', $data['scripts']);
    }

    /**
     * Ensure that the Composer binary is available and reports a version.
     */
    public function test_composer_version_command()
    {
        $output = shell_exec('composer --version');
        $this->assertIsString($output);
        $this->assertStringContainsString('Composer version', $output);
    }
}
