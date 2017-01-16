<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */

namespace Aimeos\MW\View\Engine;


class Typo3Test extends \PHPUnit_Framework_TestCase
{
	private $object;
	private $mock;


	protected function setUp()
	{
		if( !class_exists( '\TYPO3\CMS\Extbase\Object\ObjectManagerInterface' ) ) {
			$this->markTestSkipped( '\TYPO3\CMS\Extbase\Object\ObjectManagerInterface is not available' );
		}

		$this->mock = $this->getMockBuilder( '\TYPO3\CMS\Extbase\Object\ObjectManagerInterface' )
			->setMethods( array( 'get' ) )
			->disableOriginalConstructor()
			->getMock();

		$this->object = new \Aimeos\MW\View\Engine\Typo3( $this->mock );
	}


	protected function tearDown()
	{
		unset( $this->object, $this->mock );
	}


	public function testRender()
	{
		$v = new \Aimeos\MW\View\Standard( array() );

		$view = $this->getMockBuilder( 'TYPO3\\CMS\\Fluid\\View\\StandaloneView' )
			->setMethods( array( 'assignMultiple', 'render', 'setTemplatePathAndFilename' ) )
			->disableOriginalConstructor()
			->getMock();

		$view->expects( $this->once() )->method( 'setTemplatePathAndFilename' );
		$view->expects( $this->once() )->method( 'assignMultiple' );
		$view->expects( $this->once() )->method( 'render' )
			->will( $this->returnValue( 'test' ) );

		$this->mock->expects( $this->once() )->method( 'get' )
			->will( $this->returnValue( $view) );

		$result = $this->object->render( $v, 'filepath', array( 'key' => 'value' ) );
		$this->assertEquals( 'test', $result );
	}
}
