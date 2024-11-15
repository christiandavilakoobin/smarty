<?php
/**
 * Smarty PHPunit tests comments in templates
 *

 * @author  Uwe Tews
 */

/**
 * class for security test
 *
 *
 * @preserveGlobalState    disabled
 * 
 */
class CommentsTest extends PHPUnit_Smarty
{
    public function setUp(): void
    {
        $this->setUpSmarty(__DIR__);
    }

    public function testInit()
    {
        $this->cleanDirs();
    }

    /**
     * Test comments
     *
     *
     * @dataProvider        dataTestComments
     */
    public function testComments($code, $result, $testName, $testNumber)
    {
        $name = empty($testName) ? $testNumber : $testName;
        $file = "testComments_{$name}.tpl";
        $this->makeTemplateFile($file, $code);
        $this->smarty->setTemplateDir('./templates_tmp');
        $this->assertEquals($result,
                            $this->smarty->fetch($file),
                            $file);
    }

    /*
      * Data provider für testComments
      */
    public function dataTestComments()
    {
        $i = 1;
        /*
                    * Code
                    * result
                    * test name
                    * test number
                    */
        return array(array('{* this is a comment *}', '', 'T1', $i++),
                     array('{* another $foo comment *}', '', 'T2', $i++),
                     array('{* another  comment *}some in between{* another  comment *}', 'some in between',
                           'T3', $i++),
                     array("{* multi line \n comment *}", '', 'T4', $i++),
                     array('{* /* foo * / *}', '', 'T5', $i++),
                     array("A{* comment *}B\nC", "AB\nC", 'T6', $i++),
                     array("D{* comment *}\n{* comment *}E\nF", "D\nE\nF", 'T7', $i++),
                     array("G{* multi \nline *}H", "GH", 'T8', $i++),
                     array("I{* multi \nline *}\nJ", "I\nJ", 'T9', $i++),
					 array("=\n{* comment *}\n{* comment *}\n b\n{* comment *}\n{* comment *}\n=", "=\n\n\n b\n\n\n=", 'T10', $i++),
                     array("=\na\n{* comment 1 *}\n{* comment 2 *}\n{* comment 3 *}\nb\n=", "=\na\n\n\n\nb\n=", 'T11', $i++),
                     array("=\na\n{* comment 1 *}\n {* comment 2 *}\n{* comment 3 *}\nb\n=", "=\na\n\n \n\nb\n=", 'T12', $i++),
                     array("=\na\n{* comment 1 *}\n{* comment 2 *} \n{* comment 3 *}\nb\n=", "=\na\n\n \n\nb\n=", 'T13', $i++),
                     array("=\na\n{* comment 1 *}\n {* comment 2 *} \n{* comment 3 *}\nb\n=", "=\na\n\n  \n\nb\n=", 'T14', $i++),
        );
    }

    public function testTextComment5()
    {
        $this->assertEquals("I\nJ", $this->smarty->fetch("longcomment.tpl"), 'Comments longcomment.tpl');
    }
}
