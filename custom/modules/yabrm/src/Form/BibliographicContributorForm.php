<?php

namespace Drupal\yabrm\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountProxy;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for Bibliographic Contributor edit forms.
 *
 * @ingroup yabrm
 */
class BibliographicContributorForm extends ContentEntityForm {

  /**
   * For services dependency injection.
   *
   * @var Drupal\Component\Datetime\TimeInterface
   */
  protected $timeInterface;

  /**
   * For services dependency injection.
   *
   * @var Drupal\Core\Session\AccountProxy
   */
  protected $currentUser;

  /**
   * Class constructor.
   *
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository service.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle service.
   * @param \Drupal\Component\Datetime\TimeInterface $time_interface
   *   The time service.
   * @param Drupal\Core\Session\AccountProxy $current_user
   *   For services dependency injection.
   */
  public function __construct(
    EntityRepositoryInterface $entity_repository,
    EntityTypeBundleInfoInterface $entity_type_bundle_info,
    TimeInterface $time_interface,
    AccountProxy $current_user) {
    parent::__construct(
      $entity_repository,
      $entity_type_bundle_info,
      $time_interface
      );
    $this->currentUser = $current_user;
    $this->timeInterface = $time_interface;
  }

  /**
   * Object create method.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Container interface.
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.repository'),
      $container->get('entity_type.bundle.info'),
      $container->get('datetime.time'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    // Save as a new revision if requested to do so.
    if (!$form_state->isValueEmpty('revision') && $form_state->getValue('revision') != FALSE) {
      $entity->setNewRevision();

      // If a new revision is created, save the current user as revision author.
      $entity->setRevisionCreationTime($this->timeInterface->getRequestTime());
      $entity->setRevisionUserId($this->currentUser->id());
    }
    else {
      $entity->setNewRevision(FALSE);
    }

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Bibliographic Contributor.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Bibliographic Contributor.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.yabrm_contributor.canonical', ['yabrm_contributor' => $entity->id()]);
  }

}
