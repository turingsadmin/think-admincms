<?php

namespace turingAdmins\annotation\model\relation;

use Attribute;
use turingAdmins\annotation\model\Relation;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class BelongsTo extends Relation
{
    /**
     * BELONGS TO 关联定义
     * @param string $name 关联名
     * @param string $model 模型名
     * @param string $foreignKey 关联外键
     * @param string $localKey 关联主键
     * @return BelongsTo
     */
    public function __construct(
        public string $name,
        public string $model,
        public string $foreignKey = '',
        public string $localKey = ''
    )
    {
    }
}
