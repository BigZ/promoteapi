<?php
namespace bigz\Swagger2Bundle\Formatter;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class Swagger2Formatter
{
    public function format($content)
    {
        return [
            'paths' => $this->getPaths($content),
            'definitions' => $this->getDefinitions($content)
        ];
    }

    private function getDefinitions($content)
    {
        $definitions = [];
        foreach ($content as $resource) {
            /**
             * @var $annotation ApiDoc
             */
            $annotation = $resource['annotation'];
            foreach ($annotation->getParameters() as $parameter) {
                if ($parameter['actualType'] == 'model') {
                    $definitions[$this->getShortName($parameter['subType'])] = [
                        'type' => 'object',
                        'properties' => $this->getDefinitionProperties($parameter['children'])
                    ];
                }
            }
        }

        return $definitions;
    }

    private function getDefinitionProperties($children)
    {
        $properties = [];
        foreach ($children as $childName => $child) {
            $properties[$childName] = [
                'type' => $child['actualType'],
            ];
        }

        return $properties;
    }

    private function getPaths($content)
    {
        $paths = [];

        foreach ($content as $resource)
        {
            /**
             * @var $annotation ApiDoc
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
                'responses' => $this->getResponses($annotation)
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

        foreach ($resource->getParameters() as $parameterName => $parameter)
        {
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
                    '$ref' => '#/definitions/'.$this->getShortName($parameter['subType'])
                ]
            ];
        }

        return [
            'type' => $parameter['dataType']
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
                'type' => 'integer' // @TODO this may be a string. Check it out with the EntityManager
            ];
        }

        return $requirements;
    }

    private function getFilters(ApiDoc $resource)
    {
        $filters = [];

        foreach ($resource->getFilters() as $filterName => $filter) {
            $filters[] = [
                'name' => $filterName,
                'in' => 'query',
                'type' => $filter['dataType'],
                'required' => false
            ];
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
            $responses[$code]['schema'] = ['$ref' => '#/definitions/'.$this->getShortName($response['type']['class'])];
        }

        return $responses;
    }

    private function getShortName($name)
    {
        $structure = explode('\\', $name);

        return $structure[count($structure) - 1];
    }
}