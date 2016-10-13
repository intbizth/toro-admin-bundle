<?php

namespace Toro\Bundle\AdminBundle\Pager;

use Pagerfanta\View\Template\DefaultTemplate;

class DropDownTemplate extends DefaultTemplate
{
    static protected $defaultOptions = array(
        'css_dots_class'     => 'dots',
        'css_current_class'  => 'current',
        'dots_text'          => '...',
        'container_template' => '<ul class="dropdown-menu pagination-menu">%pages%</ul>',
        'page_template'      => '<li><a href="%href%">%text%</a></li>',
        'span_template'      => '<li class="%class%">%text%</li>',
    );

    public function previousDisabled()
    {
        return;
    }

    public function previousEnabled($page)
    {
        return;
    }

    public function nextDisabled()
    {
        return;
    }

    public function nextEnabled($page)
    {
        return;
    }
}
