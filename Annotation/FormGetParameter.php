<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Annotation\Target("METHOD")
 */
final class FormGetParameter extends Annotation
{
    /**
     * @Annotation\Required()
     *
     * @var string
     */
    public $formType;
}
