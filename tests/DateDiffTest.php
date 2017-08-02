<?php

namespace tests\Vice\Challenge;

use Vice\Challenge\DCI;

class DateDiffTest extends UnitTestCase
{
    public function test1DifferenceCalculatorDiff()
    {
        $startDate = '2015-12-12';
        $endDate = '2016-12-22';

        $nativeDifference = date_diff(new \DateTime($startDate), new \DateTime($endDate));
        $difference = DCI::diff($startDate, $endDate);

        $this->assertInstanceOf("Vice\Challenge\DDI", $difference);
        $this->assertEquals($nativeDifference->y, $difference->getDifferenceInYears());
        $this->assertEquals($nativeDifference->m, $difference->getDifferenceInMonths());
        $this->assertEquals($nativeDifference->d, $difference->getDifferenceInDays());
        $this->assertEquals($nativeDifference->days, $difference->getTotalDifferenceInDays());
    }

    public function test2DifferenceCalculatorDiff()
    {
        $startDate = '1999-12-12';
        $endDate = '2016-12-22';

        $nativeDifference = date_diff(new \DateTime($startDate), new \DateTime($endDate));
        $difference = DCI::diff($startDate, $endDate);

        $this->assertInstanceOf("Vice\Challenge\DDI", $difference);
        $this->assertEquals($nativeDifference->y, $difference->getDifferenceInYears());
        $this->assertEquals($nativeDifference->m, $difference->getDifferenceInMonths());
        $this->assertEquals($nativeDifference->d, $difference->getDifferenceInDays());
        $this->assertEquals($nativeDifference->days, $difference->getTotalDifferenceInDays());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument is not a date
     */
    public function test3DifferenceCalculatorDiff()
    {
        $startDate = '1999-06-24';
        $endDate = '2016-12';

        $difference = DCI::diff($startDate, $endDate);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Date input is not valid
     */
    public function test4DifferenceCalculatorDiff()
    {
        $startDate = '1999-06-24';
        $endDate = '2016-12-32';

        $difference = DCI::diff($startDate, $endDate);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Date input is not valid
     */
    public function test5DifferenceCalculatorDiff()
    {
        $startDate = '1999-02-29';
        $endDate = '2016-12-30';

        $difference = DCI::diff($startDate, $endDate);
    }
}