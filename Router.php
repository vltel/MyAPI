<?php

/**
 * Class Router
 */
class Router {
    /**
     * @var string
     */
    private $method;
    /**
     * @var string
     */
    private $query;
    /**
     * @var int
     */
    private $serviceId;
    /**
     * @var int
     */
    private $userId;
    /**
     * @var int
     */
    private $tarifId = 0;

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->query = $_SERVER['QUERY_STRING'];
        $body = json_decode( file_get_contents('php://input'), 1);
        if(!is_null($body)) {
            $this->tarifId = isset($body['tarif_id']) ? (int)$body['tarif_id'] : 0;
        }
    }

    /**
     * @param int $userId
     */
    private function setUserId($userId) {
        $this->userId = $userId;
    }

    /**
     * @param int $serviceId
     */
    private function setServiceId($serviceId) {
        $this->serviceId = $serviceId;
    }

    /**
     * @return int
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getServiceId() {
        return $this->serviceId;
    }

    /**
     * @return int
     */
    public function getTarifId() {
        return $this->tarifId;
    }

    /**
     * @return bool|string
     */
    public function route() {
        if(preg_match('/^GET users\/(\d+)\/services\/(\d+)\/tarifs$/', $this->method.' '.$this->query, $vars )) {
            $this->setUserId($vars[1]);
            $this->setServiceId($vars[2]);
            return 'getTarifs';
        } elseif(preg_match('/^PUT users\/(\d+)\/services\/(\d+)\/tarif$/', $this->method.' '.$this->query, $vars )) {
            $this->setUserId($vars[1]);
            $this->setServiceId($vars[2]);
            return 'setTarif';
        } else {
            return false;
        }
    }

}