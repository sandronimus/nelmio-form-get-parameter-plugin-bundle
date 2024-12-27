<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Model;

class TwoLevelModel
{
    public ?string $text = null;
    public ?OneLevelModel $oneLevelModel = null;
}
