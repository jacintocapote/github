<?php

namespace Drupal\Tests\github\Functional;

use Drupal\github\Plugin\Field\FieldType;
use Drupal\github\Plugin\Field\FieldWidget;
use Drupal\github\Plugin\Field\FieldFormatter;
use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\Tests\BrowserTestBase;

/**
 * Provide some tests for new custom field type github.
 *
 * @group github
 */
class GithubFieldTest extends BrowserTestBase {

  /**
   * An array of display options to pass to entity_get_display().
   *
   * @var array
   */
  protected $displayOptions;

  /**
   * A field storage to use in this test class.
   *
   * @var \Drupal\field\Entity\FieldStorageConfig
   */
  protected $fieldStorage;

  /**
   * The field used in this test class.
   *
   * @var \Drupal\field\Entity\FieldConfig
   */
  protected $field;

  /**
   * {@inheritdoc}
   */
  public static $modules = ['node', 'entity_test', 'field_ui', 'github'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $web_user = $this->drupalCreateUser([
      'access content',
      'view test entity',
      'administer entity_test content',
      'administer entity_test form display',
      'administer content types',
      'administer node fields',
    ]);

    $this->drupalLogin($web_user);
    $field_name = 'field_github';
    $type = 'github';
    $widget_type = 'github_widget';
    $formatter_type = 'github_formatter';

    $this->fieldStorage = FieldStorageConfig::create([
      'field_name' => $field_name,
      'entity_type' => 'entity_test',
      'type' => $type,
    ]);
    $this->fieldStorage->save();
    $this->field = FieldConfig::create([
      'field_storage' => $this->fieldStorage,
      'bundle' => 'entity_test',
      'required' => TRUE,
    ]);
    $this->field->save();

    EntityFormDisplay::load('entity_test.entity_test.default')
      ->setComponent($field_name, ['type' => $widget_type])
      ->save();

    $this->displayOptions = [
      'type' => $formatter_type,
      'label' => 'hidden',
    ];

    EntityViewDisplay::create([
      'targetEntityType' => $this->field->getTargetEntityTypeId(),
      'bundle' => $this->field->getTargetBundle(),
      'mode' => 'full',
      'status' => TRUE,
    ])->setComponent($field_name, $this->displayOptions)
      ->save();
  }

  /**
   * Test to check github field is working and checking github username is valid.
   */
  public function testFieldGithubCreateField() {
    $value = 'asdfñlkasjfñlaskjfasasdas';
    
    // Display creation form.
    $this->drupalGet('entity_test/add');

    // Make sure the "github_widget" widget is on the page.
    $fields = $this->xpath('//div[contains(@class, "field--widget-github-widget") and @id="edit-field-github-wrapper"]');
    $this->assertEquals(1, count($fields));

    $edit = [
      'field_github[0][value]' => $value,
    ];
    $this->drupalPostForm(NULL, $edit, 'Save');

    //Check the username inserted is a invalid user on github.
    $this->assertSession()->pageTextContains(sprintf('The github username %s is invalid. Please insert a valid username.', $value));

    //Change username to a valid github user.
    $value = 'jacintocapote';

    $edit = [
      'field_github[0][value]' => $value,
    ];
    $this->drupalPostForm(NULL, $edit, 'Save');

    // Make sure the entity was saved.
    preg_match('|entity_test/manage/(\d+)|', $this->getSession()->getCurrentUrl(), $match);
    $id = $match[1];
    $this->assertSession()->pageTextContains(sprintf('entity_test %s has been created.', $id));

  }
}
