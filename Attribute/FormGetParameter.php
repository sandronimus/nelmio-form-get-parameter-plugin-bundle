<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class FormGetParameter
{
    public string $formType;

    /**
     * @param string $formType
     */
    public function __construct(string $formType)
    {
        $this->formType = $formType;
    }
}
