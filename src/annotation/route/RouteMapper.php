<?php

namespace turingAdmins\annotation\route;

use Attribute;
use JetBrains\PhpStorm\ExpectedValues;

#[Attribute(Attribute::TARGET_METHOD)]
class RouteMapper
{
    public function __construct(
        #[ExpectedValues(['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD', '*'])]
        public string $method,
        public string $rule,
        public array  $options = []
    )
    {

    }

}
