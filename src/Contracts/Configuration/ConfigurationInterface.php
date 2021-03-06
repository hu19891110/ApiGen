<?php declare(strict_types=1);

namespace ApiGen\Contracts\Configuration;

interface ConfigurationInterface
{
    /**
     * @param mixed[] $options
     * @return mixed[]
     */
    public function resolveOptions(array $options): array;

    /**
     * @return mixed|null
     */
    public function getOption(string $name);

    /**
     * @return mixed[]
     */
    public function getOptions(): array;

    /**
     * Get property/method visibility level (public, protected or private, in binary code).
     */
    public function getVisibilityLevels(): int;

    /**
     * List of annotations.
     *
     * @return string[]
     */
    public function getAnnotationGroups(): array;

    public function getDestination(): string;

    public function getTemplatesDirectory(): string;

    public function getTemplateByName(string $name): string;

    /**
     * Get title of the project.
     */
    public function getTitle(): string;

    /**
     * Base url of application.
     */
    public function getBaseUrl(): string;

    /**
     * @return string[]
     */
    public function getSource(): array;

    public function getDestinationWithName(string $prefix): string;

    public function getDestinationWithPrefixName(string $prefix, string $name): string;
}
