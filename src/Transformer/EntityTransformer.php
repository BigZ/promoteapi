<?php

namespace App\Transformer;

use League\Fractal\TransformerAbstract;

class EntityTransformer extends TransformerAbstract
{
    public function __construct($includes)
    {
        $this->availableIncludes = $includes;
    }

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform($toFormat)
    {
        $data = $toFormat;

        if (isset($data['_links'])) {
            $data['links'] = [];

            if (isset($data['_links']['self'])) {
                $data['links']['self'] = $data['_links']['self'];
            }
            if (isset($data['_links']['next'])) {
                $data['links']['next'] = $data['_links']['next'];
            }

            unset($data['_links']);
        }

        unset($data['_embedded']);
        return $data;
    }

    public function __call($name, $arguments)
    {
        if (strpos($name, 'include') === 0) {
            $shortName = strtolower(substr($name, 7, strlen($name) - 7));
            $relation = $arguments[0]['_embedded'][$shortName];
            if (isset($relation['id'])) {
                return $this->item($relation, new EntityTransformer($this->availableIncludes), $shortName);
            }

            return $this->collection(
                $relation,
                new EntityTransformer($this->availableIncludes),
                substr($shortName, 0, strlen($shortName) - 1) // singular
            );

        }
    }
}