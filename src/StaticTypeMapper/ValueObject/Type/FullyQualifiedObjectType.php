<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\ValueObject\Type;

use Nette\Utils\Strings;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\UseItem;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @api
 */
final class FullyQualifiedObjectType extends ObjectType
{
    public function getShortNameType(): ShortenedObjectType
    {
        return new ShortenedObjectType($this->getShortName(), $this->getClassName());
    }

    public function areShortNamesEqual(AliasedObjectType | self $comparedObjectType): bool
    {
        return $this->getShortName() === $comparedObjectType->getShortName();
    }

    public function getShortName(): string
    {
        $className = $this->getClassName();
        if (! \str_contains($className, '\\')) {
            return $className;
        }

        return (string) Strings::after($className, '\\', -1);
    }

    public function getShortNameNode(): Name
    {
        $name = new Name($this->getShortName());

        // keep original to avoid loss on while importing
        $name->setAttribute(AttributeKey::NAMESPACED_NAME, $this->getClassName());

        return $name;
    }

    /**
     * @param Use_::TYPE_* $useType
     */
    public function getUseNode(int $useType): Use_
    {
        $name = new Name($this->getClassName());
        $useItem = new UseItem($name);

        $use = new Use_([$useItem]);
        $use->type = $useType;

        return $use;
    }

    public function getShortNameLowered(): string
    {
        return strtolower($this->getShortName());
    }
}
