<?php declare(strict_types=1);

namespace ApiGen\Reflection;

use ApiGen\Element\Contract\ReflectionCollector\ReflectionCollectorCollectorInterface;
use ApiGen\Reflection\Contract\Reflection\Partial\AccessLevelInterface;
use ApiGen\Reflection\Contract\Reflection\Partial\AnnotationsInterface;
use ApiGen\Reflection\Contract\Transformer\TransformerInterface;
use ApiGen\Reflection\Contract\TransformerCollectorAwareInterface;
use ApiGen\Reflection\Contract\TransformerCollectorInterface;
use ApiGen\Reflection\Exception\UnsupportedReflectionClassException;

final class TransformerCollector implements TransformerCollectorInterface
{
    /**
     * @var TransformerInterface[]
     */
    private $transformers = [];

    /**
     * @var ReflectionCollectorCollectorInterface
     */
    private $reflectionCollectorCollector;

    public function __construct(ReflectionCollectorCollectorInterface $reflectionCollectorCollector)
    {
        $this->reflectionCollectorCollector = $reflectionCollectorCollector;
    }

    public function addTransformer(TransformerInterface $transformer): void
    {
        $this->transformers[] = $transformer;
    }

    /**
     * @param object[] $reflections
     * @return object[]
     */
    public function transformGroup(array $reflections): array
    {
        $elements = [];
        foreach ($reflections as $name => $reflection) {
            $transformedReflection = $this->transformSingle($reflection);
            if ($this->shouldSkipReflection($transformedReflection)) {
                continue;
            }

            $elements[$transformedReflection->getName()] = $transformedReflection;
        }

        // @todo: sort here!, before ElementSorter
        uasort($elements, function ($firstElement, $secondElement) {
           return strcmp($firstElement->getName(), $secondElement->getName());
        });

        return $elements;
    }

    /**
     * @param object $reflection
     * @return object
     */
    public function transformSingle($reflection)
    {
        foreach ($this->transformers as $transformer) {
            if (! $transformer->matches($reflection)) {
                continue;
            }

            $newReflection = $transformer->transform($reflection);

            if ($newReflection instanceof TransformerCollectorAwareInterface) {
                $newReflection->setTransformerCollector($this);
            }

            $this->reflectionCollectorCollector->processReflection($newReflection);

            return $newReflection;
        }

        throw new UnsupportedReflectionClassException(sprintf(
            'Reflection class "%s" is not yet supported. Register new transformer implementing "%s".',
            is_object($reflection) ? get_class($reflection) : 'constant',
            TransformerInterface::class
        ));
    }

    /**
     * @param object $transformedReflection
     */
    private function hasAllowedAccessLevel($transformedReflection): bool
    {
        if (! $transformedReflection instanceof AccessLevelInterface) {
            return true;
        }

        // hardcoded @todo make service-like and using ConfigurationInterface
        if ($transformedReflection->isPublic() || $transformedReflection->isProtected()) {
            return true;
        }

        return false;
    }

    /**
     * @param object $transformedReflection
     */
    private function shouldSkipReflection($transformedReflection): bool
    {
        // also ! $this->reflection->isInternal();, remove isDocumented()

        // @let decide voters if element is passed?
        // here alreay 2 conditions
        if ($transformedReflection instanceof AnnotationsInterface
            && $transformedReflection->hasAnnotation('internal')
        ) {
            return true;
        }

        // $this->configuration->getVisibilityLevels()
        // @todo: here is the place to filter out public/protected etc - use service!
        if (! $this->hasAllowedAccessLevel($transformedReflection)) {
            return true;
        }

        return false;
    }
}
