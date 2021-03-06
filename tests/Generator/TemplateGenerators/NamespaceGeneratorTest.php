<?php declare(strict_types=1);

namespace ApiGen\Tests\Generator\TemplateGenerators;

use ApiGen\Generator\NamespaceGenerator;
use ApiGen\Generator\TraitGenerator;
use ApiGen\Reflection\Contract\ParserInterface;
use ApiGen\Tests\AbstractContainerAwareTestCase;

final class NamespaceGeneratorTest extends AbstractContainerAwareTestCase
{
    /**
     * @var TraitGenerator
     */
    private $namespaceGenerator;

    protected function setUp(): void
    {
        /** @var ParserInterface $parser */
        $parser = $this->container->getByType(ParserInterface::class);
        $parser->parseDirectories([__DIR__ . '/Source']);

        $this->namespaceGenerator = $this->container->getByType(NamespaceGenerator::class);
    }

    public function test(): void
    {
        $this->namespaceGenerator->generate();
        $this->assertFileExists(TEMP_DIR . '/namespace-ApiGen.Tests.Generator.TemplateGenerators.Source.html');
    }
}
