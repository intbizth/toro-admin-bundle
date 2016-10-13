<?php

namespace Toro\Bundle\AdminBundle\Twig;

class FlagIcon extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        $options = array('is_safe' => array('html'));

        return array(
            new \Twig_SimpleFilter('flag_icon', array($this, 'getFlagIcon'), $options),
            new \Twig_SimpleFilter('flag_icon_squared', array($this, 'getFlagIconSquared'), $options),
        );
    }

    /**
     * @param string $locale
     * @param string $tpl
     *
     * @return string
     */
    public function getFlagIcon($locale, $tpl = '<span class="%s"></span>')
    {
        $locales = explode('_', $locale);
        $locale = array_pop($locales);

        return sprintf($tpl, 'flag-icon flag-icon-' . strtolower($locale));
    }

    /**
     * @param string $locale
     * @param string $tpl
     *
     * @return string
     */
    public function getFlagIconSquared($locale, $tpl = '<span class="%s"></span>')
    {
        $locales = explode('_', $locale);
        $locale = array_pop($locales);

        return sprintf($tpl, 'flag-icon flag-icon-' . strtolower($locale) . ' flag-icon-squared');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'toro_flag_icon';
    }
}
