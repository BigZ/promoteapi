<?php

namespace bigz\halapi\Representation;

/**
 * A paginated collection representation.
 * 
 * Class PaginatedRepresentation
 * @package bigz\halapi\Representation
 */
class PaginatedRepresentation
{
    public $page;

    public $limit;

    public $_links;

    public $_embedded;

    public function __construct($page, $limit, $links, $embedded)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->_links = $links;
        $this->_embedded = $embedded;
    }
}
