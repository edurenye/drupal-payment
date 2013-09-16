<?php

/**
 * Contains \Drupal\payment\PaymentMethod.
 */

namespace Drupal\payment\Plugin\payment\method;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Component\Utility\NestedArray;
use Drupal\payment\Plugin\payment\method\PaymentMethodInterface;
use Drupal\payment\Entity\PaymentInterface;
use Drupal\payment\Entity\PaymentMethodInterface as EntityPaymentMethodInterface;

/**
 * A base payment method plugin.
 */
abstract class Base extends PluginBase implements PaymentMethodInterface {

  /**
   * The payment method entity this plugin belongs to.
   *
   * @var \Drupal\payment\Entity\PaymentMethodInterface
   */
  protected $paymentMethod;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition) {
    $configuration += $this->defaultConfiguration();
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array(
      'message_text' => '',
      'message_text_format' => 'plain_text',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(array $configuration) {
    return $this->configuration = $configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function setPaymentMethod(EntityPaymentMethodInterface $payment_method) {
    $this->paymentMethod = $payment_method;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPaymentMethod() {
    return $this->paymentMethod;
  }

  /**
   * Sets payer message text.
   *
   * @param string $text
   *
   * @return \Drupal\payment\Plugin\payment\method\PaymentMethodInterface
   */
  public function setMessageText($text) {
    $this->configuration['message_text'] = $text;

    return $this;
  }

  /**
   * Gets the payer message text.
   *
   * @return string
   */
  public function getMessageText() {
    return $this->configuration['message_text'];
  }

  /**
   * Sets payer message text format.
   *
   * @param string $format
   *   The machine name of the text format the payer message is in.
   *
   * @return \Drupal\payment\Plugin\payment\method\PaymentMethodInterface
   */
  public function setMessageTextFormat($format) {
    $this->configuration['message_text_format'] = $format;

    return $this;
  }

  /**
   * Gets the payer message text format.
   *
   * @return string
   */
  public function getMessageTextFormat() {
    return $this->configuration['message_text_format'];
  }

  /**
   * {@inheritdoc}
   */
  public function paymentFormElements(array $form, array &$form_state, PaymentInterface $payment) {
    $message = check_markup($this->getMessageText(), $this->getMessageTextFormat());
    $message = \Drupal::service('token')->replace($message, array(
      'payment' => $payment,
    ), array(
      'clear' => TRUE,
    ));
    $elements = array();
    $elements['message'] = array(
      '#type' => 'markup',
      '#markup' => $message,
    );

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function paymentMethodFormElements(array $form, array &$form_state) {
    // @todo Add a token overview, possibly when Token.module has been ported.
    $elements['#element_validate'] = array(array($this, 'paymentMethodFormElementsValidate'));
    $elements['#tree'] = TRUE;
    $elements['message'] = array(
      '#type' => 'text_format',
      '#title' => t('Payment form message'),
      '#default_value' => $this->getMessageText(),
      '#format' => $this->getMessageTextFormat(),
    );

    return $elements;
  }

  /**
   * Implements form validate callback for self::paymentMethodFormElements().
   */
  public function paymentMethodFormElementsValidate(array $element, array &$form_state, array $form) {
    $values = NestedArray::getValue($form_state['values'], $element['#parents']);
    $this->setMessageText($values['message']['value']);
    $this->setMessageTextFormat($values['message']['format']);
  }

  /**
   * {@inheritdoc}
   */
  function paymentOperationAccess(PaymentInterface $payment, $operation, $payment_method_brand) {
    if (!$this->getPaymentMethod()->status()) {
      return FALSE;
    }
    if (!$this->paymentOperationAccessCurrency($payment, $operation, $payment_method_brand)) {
      return FALSE;
    }
    if (!$this->paymentOperationAccessEvent($payment, $operation, $payment_method_brand)) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Checks a payment's currency against this plugin.
   *
   * @param \Drupal\payment\Entity\PaymentInterface $payment
   * @param string $operation
   * @param string $payment_method_brand
   *
   * @return bool
   */
  protected function paymentOperationAccessCurrency(PaymentInterface $payment, $operation, $payment_method_brand) {
    if (!$payment->id()) {
      return FALSE;
    }
    // Confirm the payment's currency is supported.
    $currencies = $this->currencies();
    if (!empty($currencies) && !isset($currencies[$payment->id()])) {
      return FALSE;
    }
    // Confirm the payment amount is higher than the supported minimum.
    if (isset($currencies[$payment->id()]['minimum']) && $payment->getAmount() < $currencies[$payment->id()]['minimum']) {
      return FALSE;
    }
    // Confirm the payment amount does not exceed the maximum.
    if (isset($currencies[$payment->id()]['maximum']) && $payment->getAmount() > $currencies[$payment->id()]['maximum']) {
      return FALSE;
    }
  }

  /**
   * Invokes events for self::paymentOperationAccess().
   *
   * @param \Drupal\payment\Entity\PaymentInterface $payment
   * @param string $operation
   * @param string $payment_method_brand
   *
   * @return bool
   */
  protected function paymentOperationAccessEvent(PaymentInterface $payment, $operation, $payment_method_brand) {
    $handler = \Drupal::moduleHandler();
    foreach ($handler->getImplementations('payment_operation_access') as $module) {
      $module_access = $handler->invoke($module, 'payment_operation_access', $payment, $this->getPaymentMethod(), $operation, $payment_method_brand);
      if ($module_access === FALSE) {
        return FALSE;
      }
    }
    // @todo invoke Rules event.

    return TRUE;
  }
}
