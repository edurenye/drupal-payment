<?php

/**
 * @file
 * Contains \Drupal\payment\Controller\EditPayment.
 */

namespace Drupal\payment\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\payment\Entity\PaymentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Handles the "edit payment" route.
 */
class EditPayment extends ControllerBase {

  /**
   * Constructs a new instance.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translator.
   */
  public function __construct(TranslationInterface $string_translation) {
    $this->stringTranslation = $string_translation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('string_translation'));
  }

  /**
   * Returns the title for the payment edit page.
   *
   * @param \Drupal\payment\Entity\PaymentInterface $payment
   *
   * @return string
   */
  public function title(PaymentInterface $payment) {
    return $this->t('Edit payment #!payment_id', [
      '!payment_id' => $payment->id(),
    ]);
  }

}
