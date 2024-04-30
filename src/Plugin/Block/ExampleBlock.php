<?php
  namespace Drupal\dummyapi\Plugin\Block;

  use Drupal\Core\Block\BlockBase;
  use GuzzleHttp\Exception\RequestException;


  /**
   * Provides an example block.
   *
   * @Block(
   *   id = "dummyapi_example",
   *   admin_label = @Translation("Example"),
   *   category = @Translation("dummyapi")
   * )
   */
  class ExampleBlock extends BlockBase {

    /**
     * {@inheritdoc}
     */
    public function build() {
      // Replace with your actual API URL
    $api_url = 'https://dummy.restapiexample.com/api/v1/employees';

    $data = $this->getApiData($api_url);
    $employee_names = [];
    $employee_salary =[];

  foreach ($data['data'] as $item) {
     $employee_names[] = $item['employee_name'];
     $employee_salary[] = $item['employee_salary'];
  }
   return [
      '#theme' => 'custom_api_block_template',
      '#employee_names' => $employee_names,
      '#employee_salary' => $employee_salary,
    ];
  }

  /**
   * Fetches data from the provided API URL.
   *
   * @param string $url
   *   The URL of the API endpoint.
   *
   * @return array|false
   *   The decoded JSON data or FALSE on failure.
   */
  protected function getApiData(string $url) {
    $client = \Drupal::httpClient();
    try {
      $response = $client->get($url);
      if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody(), TRUE);
        return $data;
      }
    } catch (\Exception $e) {
      // watchdog_error('api_data_block', 'Error fetching API data: ' . $e->getMessage());
    }
    return FALSE;
  }
}