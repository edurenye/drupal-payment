<?php

/**
 * @file
 * Definition of Drupal\payment\Entity\PaymentStatus.
 */

namespace Drupal\payment\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Core\Entity\EntityManagerInterface;

/**
 * Defines a payment status entity.
 *
 * @ConfigEntityType(
 *   admin_permission = "payment.payment_status.administer",
 *   handlers = {
 *     "access" = "\Drupal\Core\Entity\EntityAccessControlHandler",
 *     "form" = {
 *       "default" = "Drupal\payment\Entity\PaymentStatus\PaymentStatusForm",
 *       "delete" = "Drupal\payment\Entity\PaymentStatus\PaymentStatusDeleteForm"
 *     },
 *     "list_builder" = "Drupal\payment\Entity\PaymentStatus\PaymentStatusListBuilder",
 *     "storage" = "\Drupal\Core\Config\Entity\ConfigEntityStorage"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   id = "payment_status",
 *   label = @Translation("Payment status"),
 *   links = {
 *     "canonical" = "/admin/config/services/payment/status/edit/{payment_status}",
 *     "edit-form" = "/admin/config/services/payment/status/edit/{payment_status}",
 *     "delete-form" = "/admin/config/services/payment/status/edit/{payment_status}/delete"
 *   }
 * )
 */
class PaymentStatus extends ConfigEntityBase implements PaymentStatusInterface {

  /**
   * The status' description.
   *
   * @var string
   */
  protected $description;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * The entity's unique machine name.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable label.
   *
   * @var string
   */
  protected $label;

  /**
   * The plugin ID of the parent payment status.
   *
   * @var string
   */
  protected $parentId;

  /**
   * The typed config manager.
   *
   * @var \Drupal\Core\Config\TypedConfigManagerInterface
   */
  protected $typedConfigManager;

  /**
   * The entity's UUID.
   *
   * @var string
   */
  public $uuid;

  /**
   * {@inheritdoc}
   */
  public function setId($id) {
    $this->id = $id;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setLabel($label) {
    $this->label = $label;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setParentId($id) {
    $this->parentId = $id;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getParentId() {
    return $this->parentId;
  }

  /**
   * {@inheritdoc}
   */
  public function setDescription($description) {
    $this->description = $description;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    $properties = parent::toArray();
    $properties['id'] = $this->id();
    $properties['label'] = $this->label();
    $properties['parentId'] = $this->getParentId();
    $properties['description'] = $this->getDescription();

    return $properties;
  }

  /**
   * Sets the entity manager.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *
   * @return $this
   */
  public function setEntityManager(EntityManagerInterface $entity_manager) {
    $this->entityManager = $entity_manager;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function entityManager() {
    if (!$this->entityManager) {
      $this->entityManager = parent::entityManager();
    }

    return $this->entityManager;
  }

  /**
   * Sets the typed config.
   *
   * @param \Drupal\Core\Config\TypedConfigManagerInterface $typed_config_manager
   *
   * @return $this
   */
  public function setTypedConfig(TypedConfigManagerInterface $typed_config_manager) {
    $this->typedConfigManager = $typed_config_manager;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function getTypedConfig() {
    if (!$this->typedConfigManager) {
      $this->typedConfigManager = parent::getTypedConfig();
    }

    return $this->typedConfigManager;
  }

}