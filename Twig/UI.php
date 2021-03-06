<?php

namespace Toro\Bundle\AdminBundle\Twig;

class UI extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        $options = array('is_safe' => array('html'));

        return array(
            new \Twig_SimpleFunction('css_if', array($this, 'renderCssIf'), $options),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        $options = array('is_safe' => array('html'));

        return array(
            new \Twig_SimpleFilter('str_repeat', 'str_repeat', $options),
        );
    }

    public function renderCssIf($css, $comparison)
    {
        return $comparison ? $css : '';
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'toro_ui';
    }
}
