<?php

namespace Rector\Tests\Arguments\Rector\FuncCall\FunctionArgumentDefaultValueReplacerRector\Fixture;

class VersionCompare
{
    public function run()
    {
        version_compare(1, 2, '');
        version_compare(PHP_VERSION, '5.6', 'lte');
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\FuncCall\FunctionArgumentDefaultValueReplacerRector\Fixture;

class VersionCompare
{
    public function run()
    {
        version_compare(1, 2, '!=');
        version_compare(PHP_VERSION, '5.6', 'le');
    }
}

?>
