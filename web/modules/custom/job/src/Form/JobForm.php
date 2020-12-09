<?php

namespace Drupal\job\Form;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Provides a Job form.
 */
class JobForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'job_job';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
      $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      "#description" => $this->t("Name Of the Job"),
      "#maxlength" => 128,
        "#required" => true,
    ];
    $form['description'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Description'),
      "#maxlength" => 5000,
      "#required" => true,
    ];
    $form["location_type"] = [
      "#type" => 'select',
      '#title' => $this->t("Location Type"),
      "#options" => ["remote" => "Remote", "physical" =>"Physical"],
      "#size" => "1",
      "#required" => true,
    ];
    $form["location"] = [
      "#type" => 'textfield',
      '#title' => "Location",
      "#states" => [
        'visible' => [
          [':input[name="location_type"]' => ['value' => 'physical']]
        ],
        'required' => [
          [':input[name="location_type"]' => ['value' => 'physical']]
        ],
      ],
    ];
    $form["phone_number"] = [
      "#type" => 'tel',
      "#title" => $this->t("Phone Number"),
      "#field_prefix" => [
          "#type" => 'select',
          "#attributes" => ["class" => ["prefix-select"]],
          "#options" => ["GEO" => "+995", "USA" =>"+1"],
          "#size" => "1",
          "#prefix" => "<div class='prefix'>"
        ],
      "#suffix" => "</div>",
      "#required" => true,
    ];
    $form["category"] = [
      "#type" => 'entity_autocomplete',
      '#title' => $this->t("Category"),
      "#target_type" => "taxonomy_term",
      "#selection_settings" => [
        'target_bundles' => ['jobs_category'],
      ],
      '#selection_handler' => 'default',
      "#description" => $this->t("Choose Category that fits on your Job"),
      '#wrapper_attributes' => ['class' => ['flex-column'], 'id' => ["category-field"]],
      '#input_group' => TRUE,
      "#required" => true,
    ];
    $form["add_field_button"] = [
      '#type' => 'button',
      '#value' => $this->t('Add Field'),
      '#attributes' => [
        'onclick' => 'add_field(); return false',
        "class" => ["add-category"],
        'type' => 'button',
      ],
      '#attached' => array(
        'library' => array(
          'job/multiple-category-field',
        ),
      ),
    ];
    $form["remove_field_button"] = [
      '#type' => 'button',
      '#value' => $this->t('Remove Field'),
      '#attributes' => [
        'onclick' => 'remove_field(); return false',
        "class" => ["remove-category"],
        'type' => 'button',
        "disabled" => TRUE,
      ],
      '#attached' => array(
        'library' => array(
          'job/multiple-category-field',
        ),
      ),
    ];
    $form['company_email'] = [
      '#type' => 'email',
      '#title' => $this->t('Company Email'),
      "#maxlength" => 128,
      "#required" => true,
    ];
    $form["company_website"] = [
      "#type" => 'url',
      '#title' => $this->t("Company Website"),
      "#description" => $this->t("External links only, etc. https://example.com"),
      "#required" => true,
    ];
    $form["salary"] = [
      "#type" => 'number',
      '#title' => $this->t("Salary"),
      '#field_prefix' => '<div class="prefix"><p class="prefix-label">$</p>',
      "#suffix" => "</div>"
    ];
    $form["published"] = [
      "#type" => "checkbox",
      "#title" => "Published"
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Create'),
      "#attributes" => [
        'class' => ["submit-button"],
      ]
    ];


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (mb_strlen($form_state->getValue('description')['value']) < 100) {
      $form_state->setErrorByName('name', $this->t('Description should be at least 100 characters.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $new_job = Node::create([
      'type' => 'job',
      'title' => $form_state->getValue("title"),
      'body' => strip_tags($form_state->getValue('description')["value"]),
      'field_location_type' => $form_state->getValue("location_type"),
      'field_location' => $form_state->getValue("location"),
      'field_phone_number' => $form_state->getValue("phone_number"),
      'field_category' => $form_state->getValue("category"),
      'field_company_email' => $form_state->getValue("company_email"),
      'field_company_website' => $form_state->getValue("company_website"),
      'field_salary' => $form_state->getValue("salary"),
      'field_published' => $form_state->getValue("published")
    ]);
    try{
      $new_job->save();
      \Drupal::messenger()->addMessage("Job " . $form_state->getValue("title") . ' has been created');
    }catch (EntityStorageException $exception){
      \Drupal::messenger()->addError($exception);
    }
    $form_state->setRedirect('<front>');
  }

}
