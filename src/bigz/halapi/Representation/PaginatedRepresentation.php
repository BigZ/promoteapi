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
    /**
     * Filters const used to provide easy documentation integration.
     */
    const FILTERS = [
        ['name' => 'page', 'dataType' => 'integer'],
        ['name' => 'limit', 'dataType' => 'integer'],
        ['name' => 'sorting', 'dataType' => 'array'],
        ['name' => 'filtervalue', 'dataType' => 'array', 'pattern' => '[field]=(asc|desc)'],
        ['name' => 'filteroperator', 'dataType' => 'array', 'pattern' => '[field]=(<|>|<=|>=|=|!=)'],
    ];

    /**
     * @var string
     */
    public $page;

    /**
     * @var string
     */
    public $limit;

    /**
     * @var array
     */
    public $_links;

    /**
     * @var array
     */
    public $_embedded;

    /**
     * PaginatedRepresentation constructor.
     * @param $page
     * @param $limit
     * @param $links
     * @param $embedded
     */
    public function __construct($page, $limit, $links, $embedded)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->_links = $links;
        $this->_embedded = $embedded;
    }
}
