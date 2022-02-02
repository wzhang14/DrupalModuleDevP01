<?php

namespace Drupal\download_files\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Download files settings for this site.
 */
class DownloadFilesConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'download_files_download_files_config';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['download_files.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['file_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('File Types'),
      '#default_value' => $this->config('download_files.settings')->get('file_types'),
      '#options' => [
        'text/plain' => 'TEXT',
        'image/png' => 'PNG',
        'image/gif' => 'GIF',
      ],
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // if ($form_state->getValue('example') != 'example') {
    //   $form_state->setErrorByName('example', $this->t('The value is not correct.'));
    // }
    // parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('download_files.settings')
      ->set('file_types', $form_state->getValue('file_types'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
