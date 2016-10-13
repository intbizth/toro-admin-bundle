<?php

namespace Toro\Bundle\AdminBundle\Form;

use Symfony\Component\Form\FormFactory;

class Factory extends FormFactory
{
    protected $pattern = '/dos_|sylius_/';
    protected $replacement = 'toro_';

    /**
     * @param string $pattern ereg pattern
     * @param string $replacement
     */
    public function setPrefixAndReplacement($pattern = null, $replacement = null)
    {
        $this->pattern = $pattern ?: $this->pattern;
        $this->replacement = $replacement ?: $this->replacement;
    }

    /**
     * @inheritdoc
     */
    public function createNamedBuilder($name, $type = 'Symfony\Component\Form\Extension\Core\Type\FormType', $data = null, array $options = array())
    {
        if ($this->pattern) {
            $name = preg_replace($this->pattern, $this->replacement, $name);
        }

        return parent::createNamedBuilder($name, $type, $data, $options);
    }
}
