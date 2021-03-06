<?php declare(strict_types=1);

namespace ApiGen\Generator;

use ApiGen\Contracts\Configuration\ConfigurationInterface;
use ApiGen\Contracts\Generator\GeneratorInterface;
use ApiGen\Contracts\Generator\SourceCodeHighlighter\SourceCodeHighlighterInterface;
use ApiGen\Contracts\Templating\TemplateRendererInterface;
use ApiGen\Reflection\Contract\Reflection\Class_\ClassReflectionInterface;
use ApiGen\Reflection\Contract\ReflectionStorageInterface;

final class ClassGenerator implements GeneratorInterface
{
    /**
     * @var ReflectionStorageInterface
     */
    private $reflectionStorage;

    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    /**
     * @var SourceCodeHighlighterInterface
     */
    private $sourceCodeHighlighter;

    /**
     * @var TemplateRendererInterface
     */
    private $templateRenderer;

    public function __construct(
        ReflectionStorageInterface $reflectionStorage,
        ConfigurationInterface $configuration,
        SourceCodeHighlighterInterface $sourceCodeHighlighter,
        TemplateRendererInterface $templateRenderer
    ) {
        $this->reflectionStorage = $reflectionStorage;
        $this->configuration = $configuration;
        $this->sourceCodeHighlighter = $sourceCodeHighlighter;
        $this->templateRenderer = $templateRenderer;
    }

    public function generate(): void
    {
        foreach ($this->reflectionStorage->getClassReflections() as $classReflection) {
            $this->generateForClass($classReflection);
            $this->generateSourceCodeForClass($classReflection);
        }
    }

    private function generateForClass(ClassReflectionInterface $classReflection): void
    {
        $this->templateRenderer->renderToFile(
            $this->configuration->getTemplateByName('class'),
            $this->configuration->getDestinationWithPrefixName('class-', $classReflection->getName()),
            [
                'class' => $classReflection,
            ]
        );
    }

    private function generateSourceCodeForClass(ClassReflectionInterface $classReflection): void
    {
        $content = file_get_contents($classReflection->getFileName());
        $highlightedContent = $this->sourceCodeHighlighter->highlightAndAddLineNumbers($content);

        $this->templateRenderer->renderToFile(
            $this->configuration->getTemplateByName('source'),
            $this->configuration->getDestinationWithPrefixName('source-class-', $classReflection->getName()),
            [
                'activeClass' => $classReflection,
                'fileName' => $classReflection->getFileName(),
                'source' => $highlightedContent
            ]
        );
    }
}
