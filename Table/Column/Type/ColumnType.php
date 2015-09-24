<?php

namespace EMC\TableBundle\Table\Column\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use EMC\TableBundle\Table\Column\ColumnBuilderInterface;
use EMC\TableBundle\Table\Column\ColumnInterface;

/**
 * ColumnType
 * 
 * @author Chafiq El Mechrafi <chafiq.elmechrafi@gmail.com>
 */
abstract class ColumnType implements ColumnTypeInterface {

    /**
     * {@inheritdoc}
     */
    public function buildColumn(ColumnBuilderInterface $builder, array $data = null, array $options = array()) {
        
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(array &$view, ColumnInterface $column, array $data, array $options) {
        $type = $column->getType()->getName();
        
        if ( !isset($options['attrs']['class']) ) {
            $options['attrs']['class'] = '';
        }
        $options['attrs']['class'] = trim( 'column-' . $type . ' column-' . $options['name'] . ' ' . $options['attrs']['class']);
        
        $view = array(
            'name'          => $options['name'],
            'type'          => $type,
            'attrs'         => $options['attrs'],
            'value'         => static::getValue($options['format'], $data),
            'allow_sort'    => $options['allow_sort'],
            'allow_filter'  => $options['allow_filter']
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildCellView(array &$view, ColumnInterface $column, array $data) {
        
    }

    /**
     * {@inheritdoc}
     */
    public function buildFooterView(array &$view, ColumnInterface $column, array $data) {
        
    }

    /**
     * {@inheritdoc}
     */
    public function buildHeaderView(array &$view, ColumnInterface $column) {
        $view = array(
            'sort' => $column->getOption('allow_sort'),
            'title'=> $column->getOption('title')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        
        $resolver->setDefaults(array(
            'name'      => '',
            'title'     => '',
            'params'    => array(),
            'attrs'    => array(),
            'data'      => null,
            'default'   => null,
            'format'    => null,
            'allow_sort'    => false,
            'allow_filter'  => false
        ));

        $resolver->setAllowedTypes(array(
            'name'          => 'string',
            'title'         => 'string',
            'params'        => array('string', 'array'),
            'attrs'         => 'array',
            'format'        => array('null', 'string', 'callable'),
            'data'          => array('null', 'array'),
            'default'       => array('null', 'string'),
            'allow_sort'    => array('bool', 'array'),
            'allow_filter'  => array('bool', 'array')
        ));
        
        $resolver->setNormalizers(array(
            'params' => function(OptionsResolverInterface $resolver, $params) {
                return is_string($params) ? array($params) : $params;
            }
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionsResolver() {
        return new OptionsResolver();
    }
    
    static protected function getValue($format, array $data) {
        if (is_null($data) || count($data) === 0) {
            return null;
        }
        
        if (is_callable($format)) {
            return call_user_func_array($format, $data);
        }
        
        if (is_string($format)) {
            $args = $data;
            array_unshift($args, $format);
            return call_user_func_array('sprintf', $args);
        }
        
        if ( count($data) === 1 ) {
            return reset($data);
        }
        
        throw new \UnexpectedValueException;
    }
    
    /**
     * {@inheritdoc}
     */
    abstract public function getName();
}