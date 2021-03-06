<?php declare(strict_types=1);

namespace ApiGen\Reflection\Reflection\Trait_;

use ApiGen\Reflection\Contract\Reflection\AbstractParameterReflectionInterface;
use ApiGen\Reflection\Contract\Reflection\Interface_\InterfaceMethodReflectionInterface;
use ApiGen\Reflection\Contract\Reflection\Trait_\TraitMethodReflectionInterface;
use ApiGen\Reflection\Contract\Reflection\Trait_\TraitReflectionInterface;
use ApiGen\Reflection\Contract\TransformerCollectorAwareInterface;
use ApiGen\Reflection\Contract\TransformerCollectorInterface;
use phpDocumentor\Reflection\DocBlock;
use Roave\BetterReflection\Reflection\ReflectionMethod;

final class TraitMethodReflection implements TraitMethodReflectionInterface, TransformerCollectorAwareInterface
{
    /**
     * @var ReflectionMethod
     */
    private $betterMethodReflection;

    /**
     * @var DocBlock
     */
    private $docBlock;

    /**
     * @var TransformerCollectorInterface
     */
    private $transformerCollector;

    public function __construct(ReflectionMethod $betterFunctionReflection, DocBlock $docBlock)
    {
        $this->betterMethodReflection = $betterFunctionReflection;
        $this->docBlock = $docBlock;
    }

    public function getName(): string
    {
        return $this->betterMethodReflection->getName();
    }

    public function getShortName(): string
    {
        return $this->betterMethodReflection->getShortName();
    }

    public function getDeclaringTrait(): TraitReflectionInterface
    {
        return $this->transformerCollector->transformSingle(
            $this->betterMethodReflection->getDeclaringClass()
        );
    }

    public function getDeclaringTraitName(): string
    {
        return $this->getDeclaringTrait()
            ->getName();
    }

    public function getNamespaceName(): string
    {
        // TODO: Implement getNamespaceName() method.
    }

    public function isAbstract(): bool
    {
        return $this->betterMethodReflection->isAbstract();
    }

    public function isFinal(): bool
    {
        return $this->betterMethodReflection->isFinal();
    }

    public function isStatic(): bool
    {
        return $this->betterMethodReflection->isStatic();
    }

    public function getImplementedMethod(): ?InterfaceMethodReflectionInterface
    {
    }

    public function getOverriddenMethod(): void
    {
    }

    public function returnsReference(): bool
    {
        return $this->betterMethodReflection->returnsReference();
    }

    /**
     * @return AbstractParameterReflectionInterface[]
     */
    public function getParameters(): array
    {
        return $this->transformerCollector->transformGroup(
            $this->betterMethodReflection->getParameters()
        );
    }

    public function isDeprecated(): bool
    {
        // TODO: Implement isDeprecated() method.
    }

    public function getDescription(): string
    {
        // TODO: Implement getDescription() method.
    }

    /**
     * @return mixed[]
     */
    public function getAnnotations(): array
    {
        return $this->docBlock->getTags();
    }

    public function hasAnnotation(string $name): bool
    {
        return $this->docBlock->hasTag($name);
    }

    /**
     * @return mixed[]
     */
    public function getAnnotation(string $name): array
    {
        return $this->docBlock->getTagsByName($name);
    }

    public function getStartLine(): int
    {
        return $this->betterMethodReflection->getStartLine();
    }

    public function getEndLine(): int
    {
        return $this->betterMethodReflection->getEndLine();
    }

    public function isPublic(): bool
    {
        return $this->betterMethodReflection->isPublic();
    }

    public function isProtected(): bool
    {
        return $this->betterMethodReflection->isProtected();
    }

    public function isPrivate(): bool
    {
        return $this->betterMethodReflection->isPrivate();
    }

    public function setTransformerCollector(TransformerCollectorInterface $transformerCollector): void
    {
        $this->transformerCollector = $transformerCollector;
    }
}
