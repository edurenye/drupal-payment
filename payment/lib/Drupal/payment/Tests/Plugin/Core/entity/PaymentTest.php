<?php

/**
 * @file
 * Contains class \Drupal\payment\Tests\Plugin\Core\entity\PaymentTest.
 */

namespace Drupal\payment\Tests\Plugin\Core\entity;

use Drupal\payment\Plugin\Core\entity\PaymentInterface;
use Drupal\simpletest\DrupalUnitTestBase;

/**
 * Tests \Drupal\payment\Plugin\Core\entity\Payment.
 */
class PaymentTest extends DrupalUnitTestBase {

  public static $modules = array('payment', 'system');

  /**
   * {@inheritdoc}
   */
  static function getInfo() {
    return array(
      'name' => '\Drupal\payment\Plugin\Core\entity\Payment',
      'group' => 'Payment',
    );
  }

  /**
   * {@inheritdoc
   */
  function setUp() {
    parent::setUp();
    $this->payment = entity_create('payment', array());
  }

  /**
   * Tests label().
   */
  function testLabel() {
    $this->assertIdentical($this->payment->label(), 'Payment ');
  }

  /**
   * Tests setPaymentContext() and getPaymentContext().
   */
  function testGetPaymentContext() {
    $context = $this->randomName();
    $this->assertTrue($this->payment->setPaymentContext($context) instanceof PaymentInterface);
    $this->assertIdentical($this->payment->getPaymentContext(), $context);
  }

  /**
   * Tests setCurrencyCode() and getCurrencyCode().
   */
  function testGetCurrencyCode() {
    $currency_code = 'ABC';
    $this->assertTrue($this->payment->setCurrencyCode($currency_code) instanceof PaymentInterface);
    $this->assertIdentical($this->payment->getCurrencyCode(), $currency_code);
  }

  /**
   * Tests setFinishCallback() and getFinishCallback().
   */
  function testGetFinishCallback() {
    $callback = $this->randomName();
    $this->assertTrue($this->payment->setFinishCallback($callback) instanceof PaymentInterface);
    $this->assertIdentical($this->payment->getFinishCallback(), $callback);
  }

  /**
   * Tests setLineItem() and getLineItem().
   */
  function testGetLineItem() {
    $manager = \Drupal::service('plugin.manager.payment.line_item');
    $line_item = $manager->createInstance('payment_basic');
    $line_item->setName($this->randomName());
    $this->assertTrue($this->payment->setLineItem($line_item) instanceof PaymentInterface);
    $this->assertIdentical($this->payment->getLineItem($line_item->getName()), $line_item);
  }

  /**
   * Tests setLineItems() and getLineItems().
   */
  function testGetLineItems() {
    $manager = \Drupal::service('plugin.manager.payment.line_item');
    $line_item_1 = $manager->createInstance('payment_basic');
    $line_item_1->setName($this->randomName());
    $line_item_2 = $manager->createInstance('payment_basic');
    $line_item_2->setName($this->randomName());
    $this->assertTrue($this->payment->setLineItems(array($line_item_1, $line_item_2)) instanceof PaymentInterface);
    $this->assertIdentical($this->payment->getLineItems(), array(
      $line_item_1->getName() => $line_item_1,
      $line_item_2->getName() => $line_item_2,
    ));
  }

  /**
   * Tests getLineItemsByType().
   */
  function testGetLineItemsByType() {
    $type = 'payment_basic';
    $manager = \Drupal::service('plugin.manager.payment.line_item');
    $line_item = $manager->createInstance('basic');
    $this->assertTrue($this->payment->setLineItem($line_item) instanceof PaymentInterface);
    $this->assertEqual($this->payment->getLineItemsByType($type), array(
      $line_item->getName() => $line_item,
    ));
  }

  /**
   * Tests setStatus() and getStatus().
   */
  function testGetStatus() {
    $manager = \Drupal::service('plugin.manager.payment.status');
    $status = $manager->createInstance('payment_created');
    // @todo Test notifications.
    $this->assertTrue($this->payment->setStatus($status, FALSE) instanceof PaymentInterface);
    $this->assertEqual($this->payment->getStatus(), $status);
  }

  /**
   * Tests setStatuses() and getStatuses().
   */
  function testGetStatuses() {
    $manager = \Drupal::service('plugin.manager.payment.status');
    $statuses = array($manager->createInstance('payment_created'), $manager->createInstance('payment_pending'));
    $this->assertTrue($this->payment->setStatuses($statuses) instanceof PaymentInterface);
    $this->assertEqual($this->payment->getStatuses(), $statuses);
  }

  /**
   * Tests setPaymentMethodId() and getPaymentMethodId().
   */
  function testGetPaymentMethodId() {
    // @todo Test getPaymentMethod().
    $id = 5;
    $this->assertTrue($this->payment->setPaymentMethodId($id) instanceof PaymentInterface);
    $this->assertIdentical($this->payment->getPaymentMethodId(), $id);
  }

  /**
   * Tests setOwnerId() and getOwnerId().
   */
  function testGetOwnerId() {
    // @todo Test getOwner().
    $id = 5;
    $this->assertTrue($this->payment->setOwnerId($id) instanceof PaymentInterface);
    $this->assertIdentical($this->payment->getOwnerId(), $id);
  }

  /**
   * Tests getAmount().
   */
  function testGetAmount() {
    $manager = \Drupal::service('plugin.manager.payment.line_item');
    $amount = 3;
    $quantity = 2;
    for ($i = 0; $i < 2; $i++) {
      $line_item = $manager->createInstance('payment_basic');
      $line_item->setName($this->randomName());
      $line_item->setAmount($amount);
      $line_item->setQuantity($quantity);
      $this->payment->setLineItem($line_item);
    }
    $this->assertIdentical($this->payment->getAmount(), 12);
  }

  /**
   * Tests getAvailablePaymentMethods().
   */
  function testGetAvailablePaymentMethods() {
    // @todo Finish this test.
    $this->assertTrue(FALSE);
  }

  /**
   * Tests validate().
   */
  function testValidate() {
    // @todo Finish this test.
    $this->assertTrue(FALSE);
  }

  /**
   * Tests execute().
   */
  function testExecute() {
    // @todo Finish this test.
    $this->assertTrue(FALSE);
  }

  /**
   * Tests finish().
   */
  function testFinish() {
    // @todo Finish this test.
    $this->assertTrue(FALSE);
  }
}
