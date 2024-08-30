<?php

namespace turingAdmins\annotation\route;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class PatchMapper extends RouteMapper
{
    public function __construct(
        public string $rule,
        public array  $options = []
    )
    {
        parent::__construct('PATCH', $rule, $options);
    }
}