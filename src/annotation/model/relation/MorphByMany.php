<?php

namespace turingAdmins\annotation\model\relation;

use Attribute;
use turingAdmins\annotation\model\Relation;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class MorphByMany extends Relation
{
    /**
     * MORPH BY MANY关联定义
     * @param string $name 关联名
     * @param string $model 模型名
     * @param string $middle 中间表名/模型名
     * @param string|array $morph 多态字段信息
     * @param ?string $foreignKey 关联外键
     */
    public function __construct(
        public string  $name,
        public string  $model,
        public string  $middle,
        public         $morph = null,
        public ?string $foreignKey = null
    )
    {
    }
}
