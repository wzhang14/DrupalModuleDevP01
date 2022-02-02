<?php

namespace Drupal\download_files\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Provides a Download files form.
 */
class DownloadFilesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'download_files';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['file'] = [
      '#type' => 'select',
      '#title' => $this->t('File name'),
      '#description' => $this->t('Select the file that you want to download.'),
      '#required' => TRUE,
      '#options' => $this->getFiles(),
    ];

    $form['pass_phrase'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#description' => $this->t('Enter your email address to retrieve the file.'),
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Download'),
    ];

    return $form;
  }

  public function getFiles() {
    // $query = \Drupal::database()->select('file_managed', 'f')
    //   ->fields('f', ['filename', 'uri'])
    //   ->execute()
    //   ->fetchAll();
    // $files = [];
    // foreach( $query as $file) {
    //   $files[$file->uri] = $file->filename;
    // }
    // return $files;
    $config = \Drupal::config('download_files.settings');
    $file_types = $config->get('file_types');
    $fids = \Drupal::entityQuery('file')
      ->condition('status', 1)
      ->condition('filemime', $file_types, 'IN')
      ->execute();
    $files = \Drupal\file\Entity\File::loadMultiple($fids);
    $options = [];
    foreach($files as $fid => $file) {
      $options[$file->getFileUri()] = $file->getFilename();
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    $pass_phrase = $form_state->getValue('pass_phrase');
    if (!strpos($pass_phrase, 'evolvingweb.ca')) {
      $form_state->setErrorByName('pass_phrase', $this->t('Invalid email'));
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $uri = $form_state->getValue('file');
    $response = new BinaryFileResponse($uri);
    $response->setContentDisposition('attachment');
    $form_state->setResponse($response);
  }

}
