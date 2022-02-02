<?php

namespace Drupal\youtube_video_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'youtube_video_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "youtube_video_formatter",
 *   label = @Translation("Youtube Video"),
 *   description = @Translation("Display embedded video."),
 *   field_types = {
 *     "string"
 *   }
 * )
 */

class YoutubeFormatter extends FormatterBase {
  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $settings = $this->getSettings();

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#theme' => 'youtube_video',
        '#youtube_id' => $item->value,
        '#width' => $settings['width'],
        '#height' => $settings['height'],
        '#allowFullScreen' => $settings['allowFullScreen'],
      ];
    }
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'width' => '',
      'height' => '',
      'allowFullScreen' => 1,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state)
  {
    $element['width'] = [
      '#type' => 'number',
      '#title' => 'Video Width',
      '#default_value' => 560,
      '#required' => TRUE,
    ];
    $element['height'] = [
      '#type' => 'number',
      '#title' => 'Video Height',
      '#default_value' => 315,
      '#required' => TRUE,
    ];
    $element['allowFullScreen'] = [
      '#type' => 'number',
      '#title' => 'Allow full screen',
      '#default_value' => 1,
      '#required' => TRUE,
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary()
  {
    $summary =[];
    $summary[] = "The width of the video is " . $this->getSetting('width');
    $summary[] = "The height of the video is " . $this->getSetting('height');
    $summary[] = "If full screen is allow " . $this->getSetting('allowFullScreen');
    return $summary;
  }
}