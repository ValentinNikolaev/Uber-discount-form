<?php
namespace App\Action;

use App\Api\Client;
use App\Api\Exception;
use Slim\Http\Request;
use Slim\Http\Response;

class EstimateAction extends BaseAction
{
    private $queryParams = ['start_location_lng', 'start_location_lat', 'end_location_lng', 'end_location_lat'];

    private $mappingParams = [
        'start_latitude' => 'start_location_lat',
        'start_longitude' => 'start_location_lng',
        'end_latitude' => 'end_location_lng',
        'end_longitude' => 'end_location_lat'
    ];

    private $emptyEstimateRequest = [
        'start_latitude' => '0',
        'start_longitude' => '0',
        'end_latitude' => '0',
        'end_longitude' => '0'
    ];

    public function __invoke(Request $request, Response $response, $args)
    {
        $params = $request->getQueryParams();
        $this->prepareParams($params);
        $validate = $this->validateParams($params);
        if ($validate['status']) {
            $this->processRequest($params, $request, $response);
        } else {
            $this->simpleMessage($validate['message'], $response);
        }

        $this->logger->info("Estimate action dispatched. Params: " . json_encode($params));
        return $response;
    }

    /**
     * preventive params preparing
     * @param array $params
     */
    private function prepareParams(&$params = [])
    {
        if ($params) {
            foreach ($params as &$param) {
                $param = floatval($param);
            }
        }
    }

    /**
     * @todo should be moved to separate class
     * @param array $params
     * @return array
     */
    private function validateParams($params = [])
    {
        if ($params) {

            foreach ($params as $key => $queryParam) {
                if (isset($this->queryParams[$key]) && empty($queryParam)) {
                    $message = 'Value ' . $key . ' missing or empty';
                    $this->logger->warning($message . " Params:" . json_decode($params));
                    return [
                        'status' => false,
                        'message' => 'Value ' . $key . ' missing or empty',
                    ];
                }
            }

            return [
                'status' => true,
            ];
        } else {
            $message = 'Missing params';
            $this->logger->warning($message . " Params:" . json_decode($params));
            return [
                'status' => false,
                'message' => $message,
            ];
        }
    }

    /**
     * @param $params
     * @param Request $request
     * @param Response $response
     */
    private function processRequest($params, Request $request, Response $response)
    {

        try {

            $client = new Client([
                'server_token' => $this->settings['uber']['server_token'],
                'use_sandbox' => true, // optional, default false
                'version' => 'v1', // optional, default 'v1'
                'locale' => 'en_US', // optional, default 'en_US'
            ]);

            $estimateRequest = $this->emptyEstimateRequest;
            foreach ($this->mappingParams as $clientKey => $paramKey) {
                $estimateRequest[$clientKey] = $params[$paramKey];
            }

            $estimates = $client->getPriceEstimates($estimateRequest);
            $this->logger->info("Request: " . json_encode($estimateRequest));
            $this->logger->info("Estimates found: " . json_encode($estimates));

            $preparedEstimates = [];

            foreach ($estimates->prices as $price) {
                $minValue = $price->low_estimate;
                $formatted = preg_replace('/[^a-zA-Z0-9-.]/', '', $price->estimate);

                if ($price->low_estimate === $price->high_estimate) {
                    $estimateWithDiscount = $this->applyDiscount($price->low_estimate);
                } else {
                    $estimateWithDiscount = $this->applyDiscount($price->low_estimate)."-". $this->applyDiscount($price->high_estimate);
                }

                $preparedEstimates[$minValue . "_" . mt_rand(0, 200)] = [
                    'display_name' => $this->settings['after_prefix'].$price->display_name,
                    'estimate_with_discount' => $estimateWithDiscount,
                    'estimate_formatted' => $formatted,
                    'currency_code' => $price->currency_code,
                ];
            }
            ksort($preparedEstimates);

            if (!$preparedEstimates) {
                $this->simpleMessage('Sorry! Nothing found.', $response);
            } else {
                $this->view->render($response, 'estimates.twig',
                    ['estimates' => $preparedEstimates, 'discount' => ($this->settings['discount'] * 100) . " %"]);
            }


        } catch (Exception $e) {
            $this->logger->debug($e->getMessage());
            $this->simpleMessage($e->getMessage(), $response);
        }
    }

    /**
     * @param $value
     * @return mixed
     */
    private function applyDiscount($value)
    {
        return is_numeric($value) ? $value * (1 - $this->settings['discount']) : $value;
    }

    /**
     * @param $message
     * @param $response
     */
    private function simpleMessage($message, Response &$response)
    {
        $this->view->render($response, 'message.twig', ['message' => $message]);
    }

}