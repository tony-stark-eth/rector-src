<?php

namespace Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Fixture;

use Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeClass;
use Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum;

final class SkipUsageOfGetValueForClass
{
    public function run($value)
    {
        $obj = new SomeClass();
        $objName = $obj->getValue();
    }
}

?>
