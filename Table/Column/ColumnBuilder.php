<?php

namespace EMC\TableBundle\Table\Column;

use EMC\TableBundle\Table\Column\Type\ColumnTypeInterface;

/**
 * ColumnBuilder
 *
 * @author Chafiq El Mechrafi <chafiq.elmechrafi@gmail.com>
 */
class ColumnBuilder implements ColumnBuilderInterface {

    /**
     * @var ColumnTypeInterface
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    function __construct(ColumnTypeInterface $type, $options) {
        $this->type = $type;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumn() {
        return new Column($this->type, $this->options);
    }

}
