<?php

/**
 * @file
 * Contains \Drupal\payment\Tests\Plugin\Payment\Type\PaymentTypeBaseUnitTest.
 */

namespace Drupal\payment\Tests\Plugin\Payment\Type;

use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\payment\Plugin\Payment\Type\PaymentTypeBase
 */
class PaymentTypeBaseUnitTest extends UnitTestCase {

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $moduleHandler;

  /**
   * The payment type under test.
   *
   * @var \Drupal\payment\Plugin\Payment\Type\PaymentTypeBase|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $paymentType;

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'description' => '',
      'name' => '\Drupal\payment\Plugin\Payment\Type\PaymentTypeBase unit test',
      'group' => 'Payment',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    $this->moduleHandler = $this->getMock('\Drupal\Core\Extension\ModuleHandlerInterface');

    $configuration = array();
    $plugin_id = $this->randomName();
    $plugin_definition = array();
    $this->paymentType = $this->getMockBuilder('\Drupal\payment\Plugin\Payment\Type\PaymentTypeBase')
      ->setConstructorArgs(array($configuration, $plugin_id, $plugin_definition, $this->moduleHandler))
      ->setMethods(array('paymentDescription'))
      ->getMock();
  }

  /**
   * @covers ::resumeContext
   */
  public function testResumeContext() {
    $this->moduleHandler->expects($this->once())
      ->method('invokeAll')
      ->with('payment_type_pre_resume_context');
    $this->paymentType->resumeContext();
  }

  /**
   * @covers ::setPayment
   * @covers ::getPayment
   */
  public function testGetPayment() {
    $payment = $this->getMockBuilder('\Drupal\payment\Entity\Payment')
      ->disableOriginalConstructor()
      ->getMock();
    $this->assertSame(spl_object_hash($this->paymentType), spl_object_hash($this->paymentType->setPayment($payment)));
    $this->assertSame(spl_object_hash($payment), spl_object_hash($this->paymentType->getPayment()));
  }
}
