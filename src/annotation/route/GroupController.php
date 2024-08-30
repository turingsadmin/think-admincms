<?php

namespace turingAdmins\annotation\route;
use Attribute;
#[Attribute(Attribute::TARGET_CLASS)]
final class GroupController
{
    public function __construct(public string $name, public array $options = [])
    {
    }
}