<?php

/**
 * Contains \Drupal\payment\Plugin\payment\status\Base.
 */

namespace Drupal\payment\Plugin\payment\status;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\payment\Payment;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A base payment status.
 */
abstract class Base extends PluginBase implements ContainerFactoryPluginInterface, PaymentStatusInterface {

  /**
   * The payment status plugin manager.
   *
   * @var \Drupal\payment\Plugin\payment\status\Manager
   */
  protected $paymentStatusManager;

  /**
   * Constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\payment\Plugin\payment\status\Manager $payment_status_manager
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, Manager $payment_status_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->paymentStatusManager = $payment_status_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, array $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, Manager);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array(
      'created' => NULL,
      'id' => 0,
      'paymentId' => 0,
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
  public function setCreated($created) {
    $this->configuration['created'] = $created;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreated() {
    return $this->configuration['created'];
  }

  /**
   * {@inheritdoc}
   */
  public function setPaymentId($paymentId) {
    $this->configuration['paymentId'] = $paymentId;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPaymentId() {
    return $this->configuration['paymentId'];
  }

  /**
   * {@inheritdoc}
   */
  public function setId($id) {
    $this->configuration['id'] = $id;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getId() {
    return $this->configuration['id'];
  }

  /**
   * {@inheritdoc}
   */
  function getAncestors(){
    return $this->paymentStatusManager->getAncestors($this->getPluginId());
  }

  /**
   * {@inheritdoc}
   */
  public function getChildren() {
    return $this->paymentStatusManager->getChildren($this->getPluginId());
  }

  /**
   * {@inheritdoc}
   */
  function getDescendants() {
    return $this->paymentStatusManager->getDescendants($this->getPluginId());
  }

  /**
   * {@inheritdoc}
   */
  function hasAncestor($plugin_id) {
    return $this->paymentStatusManager->hasAncestor($this->getPluginId(), $plugin_id);
  }

  /**
   * {@inheritdoc}
   */
  function isOrHasAncestor($plugin_id) {
    return $this->paymentStatusManager->isOrHasAncestor($this->getPluginId(), $plugin_id);
  }

  /**
   * {@inheritdoc}
   */
  public static function getOperations($plugin_id) {
    return array();
  }
}
