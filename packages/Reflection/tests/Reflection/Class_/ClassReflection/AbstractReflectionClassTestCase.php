<?php declare(strict_types=1);

namespace ApiGen\Reflection\Tests\Reflection\Class_\ClassReflection;

use ApiGen\Reflection\Contract\ParserInterface;
use ApiGen\Reflection\Contract\Reflection\Class_\ClassReflectionInterface;
use ApiGen\Reflection\Contract\Reflection\Interface_\InterfaceReflectionInterface;
use ApiGen\Reflection\Contract\ReflectionStorageInterface;
use ApiGen\Reflection\Tests\Reflection\Class_\ClassReflection\Source\AccessLevels;
use ApiGen\Reflection\Tests\Reflection\Class_\ClassReflection\Source\ParentClass;
use ApiGen\Reflection\Tests\Reflection\Class_\ClassReflection\Source\RichInterface;
use ApiGen\Reflection\Tests\Reflection\Class_\ClassReflection\Source\SomeTrait;
use ApiGen\Tests\AbstractContainerAwareTestCase;

abstract class AbstractReflectionClassTestCase extends AbstractContainerAwareTestCase
{
    /**
     * @var ClassReflectionInterface
     */
    protected $reflectionClass;

    /**
     * @var ClassReflectionInterface
     */
    protected $reflectionClassOfParent;

    /**
     * @var ClassReflectionInterface
     */
    protected $reflectionClassOfTrait;

    /**
     * @var InterfaceReflectionInterface
     */
    protected $interfaceReflection;

    protected function setUp(): void
    {
        /** @var ParserInterface $parser */
        $parser = $this->container->getByType(ParserInterface::class);
        $parser->parseDirectories([__DIR__ . '/Source']);

        /** @var ReflectionStorageInterface $reflectionStorage */
        $reflectionStorage = $this->container->getByType(ReflectionStorageInterface::class);

        $classReflections = $reflectionStorage->getClassReflections();
        $this->reflectionClass = $classReflections[AccessLevels::class];
        $this->reflectionClassOfParent = $classReflections[ParentClass::class];

//        $traitReflections = $reflectionStorage->getTraitReflections();
//        $this->reflectionClassOfTrait = $traitReflections[SomeTrait::class];

        $interfaceReflections = $reflectionStorage->getInterfaceReflections();
        $this->interfaceReflection = $interfaceReflections[RichInterface::class];
    }
}
