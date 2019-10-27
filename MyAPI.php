<?php

/**
 * Class MyAPI
 */
class MyAPI {
    /**
     * @var DataBase
     */
    private $db;
    /**
     * @var Router
     */
    private $route;

    /**
     * @param DataBase $db
     * @param Router $route
     */
    public function __construct(DataBase $db, Router $route) {
        $this->db = $db;
        $this->route = $route;
    }

    /**
     * @return array
     */
    public function getTarifs() {
        $a = array();
        $query = 'select
                    t.*
                  from
                    services s,
                    tarifs t
                  where
                    s.ID = ?
                    and s.user_id = ?
                    and s.tarif_id = t.ID
                  ';
        $rows = $this->db->Query($query, array($this->route->getServiceId(), $this->route->getUserId()));
        if(count($rows)>0) {
            $a['result'] = 'ok';
            $tarifs = array();
            foreach ($rows as $key => $curTarif) {
                $tarifGroup = array();
                $tarifGroupId = $curTarif['tarif_group_id'];
                $queryGroup = " select
                                  ID,
                                  title,
                                  price,
                                  pay_period,
                                  concat(unix_timestamp(curdate() + interval pay_period day), '+0300') new_payday,
                                  speed
                                from
                                  tarifs
                                where
                                  tarif_group_id = ?
                                ";
                $rowsGroup = $this->db->Query($queryGroup, array($tarifGroupId));
                foreach($rowsGroup as $keyGroup => $group) {
                    $tarifGroup[] = $group;
                }
                $tarifs[] = array(
                    'title'  => $curTarif['title'],
                    'link'   => $curTarif['link'],
                    'speed'  => $curTarif['speed'],
                    'tarifs' => $tarifGroup,
                );
            }
            $a['tarifs'] = $tarifs;
        } else {
            $a['result'] = 'error';
        }
        return $a;
    }

    /**
     * @return array
     */
    public function setTarif() {
        $a = array();
        if($this->route->getTarifId() == 0) {
            $a['result'] = 'error';
        } else {
            $a['result'] = 'ok';
            $query = 'select
                        count(*) kol
                      from
                        services
                      where
                        ID=?
                        and user_id=?
                      ';
            $rowsKol = $this->db->Query($query, array(  $this->route->getServiceId(), $this->route->getUserId()));
            if(!isset($rowsKol[0]['kol']) || $rowsKol[0]['kol'] == 0) {
                $a['result'] = 'error';
            } else {
                $query = 'update
                            services
                          set
                            tarif_id=?,
                            payday=?
                          where
                            ID=?
                            and user_id=?
                           ';
                $rowsUpd = $this->db->Query($query, array( $this->route->getTarifId(),
                                                           date('Y-m-d'),
                                                           $this->route->getServiceId(),
                                                           $this->route->getUserId()));
            }
        }
        return $a;

    }

} 