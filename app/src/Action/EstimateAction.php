<?php
namespace App\Action;

use Slim\Http\Request;
use Slim\Http\Response;

class EstimateAction extends BaseAction
{
    private $queryParams = ['start_location', 'end_location'];
    private $geoLocation = ['lng', 'lat'];

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

            foreach ($this->queryParams as $queryParam) {
                foreach ($this->geoLocation as $suffix) {
                    $lookupKey = $queryParam . "_" . $suffix;
                    if (empty($lookupKey)) {
                        return [
                            'status' => false,
                            'message' => 'Value ' . $lookupKey . ' missing or wrong',
                        ];
                    }
                }
            }

            return [
                'status' => true,
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Missing params',
            ];
        }
    }

    private function processRequest($params, Request $request, Response $response)
    {

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