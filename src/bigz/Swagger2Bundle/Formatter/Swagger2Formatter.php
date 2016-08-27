<?php

namespace bigz\Swagger2Bundle\Formatter;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Proxy\Proxy;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use phpDocumentor\Reflection\DocBlockFactory;

class Swagger2Formatter
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * @var DocBlockFactory
     */
    private $docBlockFactory;

    public function __construct(
        EntityManager $entityManager,
        Reader $annotationReader
    ) {
        $this->entityManager = $entityManager;
        $this->annotationReader = $annotationReader;
        $this->docBlockFactory = DocBlockFactory::createInstance();
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function format($content)
    {
        $formattedContent = [
            'paths' => $this->getPaths($content),
            'definitions' => $this->getDefinitions($content),
        ];

        if (isset($this->config['security']['type'])) {
            $formattedContent['securityDefinitions'] = $this->getSecurity();
            $formattedContent['security'][] = ['UserSecurity' => []];
        }

        $header = $this->config;
        unset($header['security']);

        return array_merge($header, $formattedContent);
    }

    private function getSecurity()
    {
        if ($this->config['security']['type'] == 'basic') {
            return ['UserSecurity' => ['type' => 'basic']];
        }

        if ($this->config['security']['type'] == 'apiKey') {
            return ['UserSecurity' => [
                'type' => 'apiKey',
                'in' => $this->config['security']['in'],
                'name' => $this->config['security']['name'],
            ]];
        }

        // TODO oauth implementation
    }

    private function getDefinitions($content)
    {
        return array_merge($this->getFormTypeDefinitions($content), $this->getEntityDefinitions($content));
    }

    private function getFormTypeDefinitions($content)
    {
        $definitions = [];

        foreach ($content as $resource) {
            /**
             * @var ApiDoc
             */
            $annotation = $resource['annotation'];
            foreach ($annotation->getParameters() as $parameter) {
                if ($parameter['actualType'] == 'model') {
                    $definitions[$this->getShortName($parameter['subType'])] = [
                        'type' => 'object',
                        'properties' => $this->getDefinitionProperties($parameter['children']),
                    ];
                }
            }
        }

        return $definitions;
    }

    private function getEntityDefinitions($content)
    {
        $definitions = [];

        foreach ($content as $resource) {
            /**
             * @var ApiDoc
             */
            $annotation = $resource['annotation'];
            $className = $annotation->getOutput();
            if (!$className || !class_exists($className)) {
                continue;
            }

            $definitions[$this->getShortName($className)] = [
                'type' => 'object',
                'properties' => $this->isEntity($className) ?
                    $this->getEntityFields($className) : $this->getClassFields($className),
            ];
        }

        return $definitions;
    }

    private function getEntityFields($className)
    {
        $reflectionClass = new \ReflectionClass($className);
        $properties = [];

        // TODO exclusion strategy
        foreach ($reflectionClass->getProperties() as $property) {
            $annotations = $this->annotationReader->getPropertyAnnotations($property);

            foreach ($annotations as $annotation) {
                if ($annotation instanceof Column) {
                    $type = $annotation->type;
                    $properties[$property->getName()] = [];
                    if (in_array($type, ['datetime', 'text'])) {
                        $properties[$property->getName()]['format'] = $type;
                        $type = 'string';
                    }

                    $properties[$property->getName()]['type'] = $type;
                }
                if (
                    $annotation instanceof OneToMany ||
                    $annotation instanceof OneToOne
                ) {
                    $properties[$property->getName()] = ['type' => 'integer'];
                }

                if ($annotation instanceof ManyToMany) {
                    $properties[$property->getName()] = ['type' => 'array', 'items' => ['type' => 'integer']];
                }
            }
        }

        return $properties;
    }

    private function getClassFields($className)
    {
        try {
            $reflectionClass = new \ReflectionClass($className);
        } catch (\ReflectionException $exception) {
            return  $className;
        }

        $properties = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $docblock = $this->docBlockFactory->create($property);
            $tags = $docblock->getTagsByName('var');
            if (is_array($tags)) {
                $type = (string) $tags[0]->getType();
                $properties[$property->getName()] = [];
                if (in_array($type, ['datetime', 'text'])) {
                    $properties[$property->getName()]['format'] = $type;
                    $type = 'string';
                }
                $properties[$property->getName()]['type'] = $type;

                // TODO it seems a bit hardcoded, right ?
                if ($type == 'array') {
                    $properties[$property->getName()]['items'] = ['type' => 'object'];
                }
            }
        }

        return $properties;
    }

    /**
     * Fields of the form type.
     *
     * @param $children
     *
     * @return array
     */
    private function getDefinitionProperties($children)
    {
        $properties = [];
        foreach ($children as $childName => $child) {
            $type = (string) $child['actualType'];
            $properties[$childName] = [];

            if (in_array($type, ['datetime', 'text'])) {
                $properties[$childName]['format'] = $type;
                $type = 'string';
            }

            $properties[$childName]['type'] = $type;

            if ($type == 'file') {
                unset($properties[$childName]);
            }
        }

        return $properties;
    }

    private function getPaths($content)
    {
        $paths = [];

        foreach ($content as $resource) {
            /**
             * @var ApiDoc
             */
            $annotation = $resource['annotation'];
            $resourceName = $annotation->getRoute()->getPath();

            if (empty($paths[$resourceName])) {
                $paths[$resourceName] = [];
            }

            $path = array_filter([
                'summary' => $annotation->getDescription(),
                'description' => $annotation->getDocumentation(),
                'parameters' => $this->getParameters($annotation),
                'responses' => $this->getResponses($annotation),
            ]);
            $paths[$resourceName][strtolower($annotation->getMethod())] = $path;
        }

        return $paths;
    }

    private function getParameters(ApiDoc $resource)
    {
        $result = [];
        if ($resource->getRequirements()) {
            $result = $this->getRequirements($resource);
        }

        foreach ($this->getFilters($resource) as $filter) {
            $result[] = $filter;
        }

        foreach ($resource->getParameters() as $parameterName => $parameter) {
            $values = array_merge(
                [
                    'name' => $parameterName,
                    'required' => $parameter['required'],
                ],
                $this->getType($parameter)
            );

            if (isset($parameter['description']) && $parameter['description']) {
                $values['description'] = $parameter['description'];
            }

            $result[] = $values;
        }

        return $result;
    }

    private function getType(array $parameter)
    {
        if ($parameter['actualType'] == 'model') {
            return [
                'in' => 'body',
                'schema' => [
                    '$ref' => '#/definitions/'.$this->getShortName($parameter['subType']),
                ],
            ];
        }

        return [
            'type' => $parameter['dataType'],
        ];
    }

    private function getRequirements(ApiDoc $annotation)
    {
        if (!is_array($annotation->getRequirements())) {
            return [];
        }

        $requirements = [];
        foreach ($annotation->getRequirements() as $requirementName => $requirement) {
            $requirements[] = [
                'name' => $requirementName,
                'in' => 'path',
                'required' => true,
                'x-example' => 1, // @TODO take it from somewhere maybe ?
                'type' => 'integer', // @TODO this may be a string. Check it out with the EntityManager
            ];
        }

        return $requirements;
    }

    private function getFilters(ApiDoc $resource)
    {
        $filters = [];

        foreach ($resource->getFilters() as $filterName => $filter) {
            $swaggFilter = [
                'name' => $filterName,
                'in' => 'query',
                'type' => $filter['dataType'],
                'required' => false,
            ];

            if (isset($filter['default'])) {
                $swaggFilter['default'] = $filter['default'];
            }

            if (isset($filter['pattern'])) {
                $swaggFilter['pattern'] = $filter['pattern'];
            }

            // TODO it seems a bit hardcoded, right ?
            if ($filter['dataType'] == 'array') {
                $swaggFilter['items'] = ['type' => 'string'];
            }

            $filters[] = $swaggFilter;
        }

        return $filters;
    }

    private function getResponses(ApiDoc $annotation)
    {
        $responses = [];

        foreach ($annotation->getStatusCodes() as $code => $status) {
            $responses[$code] = [
                'description' => $status[0],

            ];
        }

        foreach ($annotation->getParsedResponseMap() as $code => $response) {
            if (class_exists($response['type']['class'])) {
                $responses[$code]['schema'] = ['$ref' => '#/definitions/'.$this->getShortName($response['type']['class'])];
            }
            if (isset($responses[$code]) && empty($responses[$code]['description'])) {
                $responses[$code]['description'] = 'Default Response';
            }
        }

        if (!$responses) {
            $responses[200] = ['description' => 'Success'];
            $responses[400] = ['description' => 'Error'];
        }

        return $responses;
    }

    /**
     * @param EntityManager $em
     * @param string|object $class
     *
     * @return bool
     */
    public function isEntity($class)
    {
        if (is_object($class)) {
            $class = ($class instanceof Proxy)
                ? get_parent_class($class)
                : get_class($class);
        }

        return !$this->entityManager->getMetadataFactory()->isTransient($class);
    }

    private function getShortName($name)
    {
        $structure = explode('\\', $name);

        return $structure[count($structure) - 1];
    }
}
