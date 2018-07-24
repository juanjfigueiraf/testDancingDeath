<?php

namespace JSC\ConnectorBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Jaspersoft\Client\Client;
use DWD\TestAsimovBundle\Entity\datesWithDeath;

class DefaultController extends FOSRestController
{
    /**
     * ruta ejemplo (en el navegador): api/shipment/report?ShipId=1&nombre=juan
     * @Route("/api/shipment/report")
     * @Method({"GET"})
     */
    public function getShipmentReportAction($id, Request $request)
    {
        /*
        return new Response('Bienvenido a mi módulo de embarques JSC');
        */

        /*
        $c = new \Jaspersoft\Client\Client("http://127.0.0.1:8081/jasperserver", "jasperadmin", "jasperadmin", "");
        $controls = array("ShipId"=>$id);
        $report = $c->reportService()->runReport('/Olocis/Remarks.jrxml', 'pdf', null, null, $controls);//Prueba_Juan
        return new Response($report, 200, array(
            'Content-Type' => 'application/pdf'
            )
        );
        */
        
        
        $c = new \Jaspersoft\Client\Client("http://127.0.0.1:8081/jasperserver", "jasperadmin", "jasperadmin", "");
        $controls = array("ShipId"=>$id);
        $report = $c->reportService()->runReport('/olocis/ExecutiveReport', 'pdf', null, null, $controls);//Prueba_Juan //ExecutiveReport
        return new Response($report, 200, array(
            'Content-Type' => 'application/pdf'
            )
        );
        

        /*
        $c = new \Jaspersoft\Client\Client("http://127.0.0.1:8081/jasperserver", "jasperadmin", "jasperadmin", "");
        $info = $c->serverInfo();
        $view = View::create();
        $view->setData(array("info"=>$info));
        return $this->handleView($view);
        */
        
        /*
        $view = View::create();
        $id = $request->query->get("ShipId");
        $nombre = $request->query->get("nombre");
        $view->setData(array("id"=>$id, "nombre"=>$nombre));
        return $this->handleView($view);
        */
        
        /*
        $view = View::create();
        $id = 1;
        $nombre = 'juan';
        $view->setData(array("id"=>$id, "nombre"=>$nombre));
        return $this->handleView($view);
        */
    }

    /**
     * @Route("/api/remarks/report")
     * @Method({"GET"})
     */
    public function getRemarksReportAction($id, Request $request)
    {
        
       $c = new \Jaspersoft\Client\Client("http://127.0.0.1:8081/jasperserver", "jasperadmin", "jasperadmin", "");
        $controls = array("ShipId"=>$id);
        $report = $c->reportService()->runReport('/olocis/PortLog', 'pdf', null, null, $controls);//Prueba_Juan
        return new Response($report, 200, array(
            'Content-Type' => 'application/pdf'
            )
        );
    }
    
    /**
     * @Route("/api/obqrob/report")
     * @Method({"GET"})
     */
    public function getObqRobReportAction($id)
    {
        $em = $this->getDoctrine()->getManager();
    /*
     * Determinación del MomoentMeas
     */
        $query = $em->createQuery(
                                    'SELECT c
                                     FROM OLOShipmentBundle:Shipment a,
                                          OLOCatalogBundle:CatVal b,
                                          OLOCatalogBundle:CatValAtt c
                                     WHERE a.shipId = :id
                                          and a.operId = b.catValId
                                          and b.catValId = c.catValId
                                          and c.attId like :attributeType'
            )->setParameters(array('id' => $id, 'attributeType' => 'Type'));
        
        $operationObject = $query->getSingleResult();
        
        if($operationObject->getAttVal() == 'Loading')
            {
                $momentMeasString = 'B';
            }
                else{
                    $momentMeasString = 'A';
                }
        /*
         * Determinación del tipo de cargo (si al menos 1 cargo es asfalto se imprime reporte de asfalto)
         */ 
            $query = $em->createQuery(
                                        'SELECT c
                                         FROM OLOShipmentBundle:Shipment s,
                                              OLOCargoBundle:Cargo a,
                                              OLOCatalogBundle:CatVal b,
                                              OLOCatalogBundle:CatValAtt c
                                         WHERE s.shipId = :id
                                              and s.shipId = a.shipId
                                              and a.cargoId = b.catValId
                                              and b.catValId = c.catValId
                                              and c.attId like :attributeType'
                )->setParameters(array('id' => $id, 'attributeType' => 'Type'));
            
            $cargoTypeObjects = $query->getResult();

            foreach ($cargoTypeObjects as $cargoTypeObject) {
                if($cargoTypeObject->getAttVal() == 'Asphalt')
                {
                    $asphalt = 'Y';
                    break 1;
                }
                    else{
                        $asphalt = 'N';
                    }
            }

            if($asphalt == 'Y')
            {
                $rutaReport = '/olocis/ObqRobASP';
            }
                else{
                    $rutaReport = '/olocis/ObqRobCP';
                }
        
        /*
         * Impresion de reporte
         */ 
        $c = new \Jaspersoft\Client\Client("http://127.0.0.1:8081/jasperserver", "jasperadmin", "jasperadmin", "");
        $controls = array("ShipId"=>$id, "Moment"=> $momentMeasString);
        $report = $c->reportService()->runReport($rutaReport, 'pdf', null, null, $controls);//Prueba_Juan
        return new Response($report, 200, array(
            'Content-Type' => 'application/pdf'
            )
        );
    }
    
    /**
     * @Route("/api/ullage/report")
     * @Method({"GET"})
     */
    public function getUllageReportAction($id)
    {
        $em = $this->getDoctrine()->getManager();
    /*
     * Determinación del MomoentMeas
     */
        $query = $em->createQuery(
                                    'SELECT c
                                     FROM OLOShipmentBundle:Shipment a,
                                          OLOCatalogBundle:CatVal b,
                                          OLOCatalogBundle:CatValAtt c
                                     WHERE a.shipId = :id
                                          and a.operId = b.catValId
                                          and b.catValId = c.catValId
                                          and c.attId like :attributeType'
            )->setParameters(array('id' => $id, 'attributeType' => 'Type'));
        
        $operationObject = $query->getSingleResult();
        
        if($operationObject->getAttVal() == 'Loading')
            {
                $momentMeasString = 'A';
            }
                else{
                    $momentMeasString = 'B';
                }
        /*
         * Determinación del tipo de cargo (si al menos 1 cargo es asfalto se imprime reporte de asfalto)
         */ 
            $query = $em->createQuery(
                                        'SELECT c
                                         FROM OLOShipmentBundle:Shipment s,
                                              OLOCargoBundle:Cargo a,
                                              OLOCatalogBundle:CatVal b,
                                              OLOCatalogBundle:CatValAtt c
                                         WHERE s.shipId = :id
                                              and s.shipId = a.shipId
                                              and a.cargoId = b.catValId
                                              and b.catValId = c.catValId
                                              and c.attId like :attributeType'
                )->setParameters(array('id' => $id, 'attributeType' => 'Type'));
            
            $cargoTypeObjects = $query->getResult();

            foreach ($cargoTypeObjects as $cargoTypeObject) {
                if($cargoTypeObject->getAttVal() == 'Asphalt')
                {
                    $asphalt = 'Y';
                    break 1;
                }
                    else{
                        $asphalt = 'N';
                    }
            }

            if($asphalt == 'Y')
            {
                $rutaReport = '/olocis/UllageASP';
            }
                else{
                    $rutaReport = '/olocis/UllageCP';
                }
                
        /*
         * Impresion de reporte
         */ 
        $c = new \Jaspersoft\Client\Client("http://127.0.0.1:8081/jasperserver", "jasperadmin", "jasperadmin", "");
        $controls = array("ShipId"=>$id, "Moment"=> $momentMeasString);
        $report = $c->reportService()->runReport($rutaReport, 'pdf', null, null, $controls);//Prueba_Juan
        return new Response($report, 200, array(
            'Content-Type' => 'application/pdf'
            )
        );
    }
    
    /**
     * @Route("/api/shore/report")
     * @Method({"GET"})
     */
    public function getShoreReportAction($id)
    {
        
       $c = new \Jaspersoft\Client\Client("http://127.0.0.1:8081/jasperserver", "jasperadmin", "jasperadmin", "");
        $controls = array("ShipId"=>$id);
        $report = $c->reportService()->runReport('/olocis/ShoreCP', 'pdf', null, null, $controls);//Prueba_Juan
        return new Response($report, 200, array(
            'Content-Type' => 'application/pdf'
            )
        );
    }
    
    /**
     * @Route("/api/vef/report")
     * @Method({"GET"})
     */
    public function getVefReportAction($id)
    {
        
       $c = new \Jaspersoft\Client\Client("http://127.0.0.1:8081/jasperserver", "jasperadmin", "jasperadmin", "");
        $controls = array("ShipId"=>$id);
        $report = $c->reportService()->runReport('/olocis/VEF', 'pdf', null, null, $controls);//Prueba_Juan
        return new Response($report, 200, array(
            'Content-Type' => 'application/pdf'
            )
        );
    }
    
    /**
     * @Route("/api/checkeval/report")
     * @Method({"GET"})
     */
    public function getCheckEvalReportAction($id)
    {
        
       $c = new \Jaspersoft\Client\Client("http://127.0.0.1:8081/jasperserver", "jasperadmin", "jasperadmin", "");
        $controls = array("ShipId"=>$id);
        $report = $c->reportService()->runReport('/olocis/InspectorCheck', 'pdf', null, null, $controls);//Prueba_Juan
        return new Response($report, 200, array(
            'Content-Type' => 'application/pdf'
            )
        );
    }
    
    /**
     * @Route("/api/checkapi/report")
     * @Method({"GET"})
     */
    public function getCheckApiReportAction($id)
    {
        
       $c = new \Jaspersoft\Client\Client("http://127.0.0.1:8081/jasperserver", "jasperadmin", "jasperadmin", "");
        $controls = array("ShipId"=>$id);
        $report = $c->reportService()->runReport('/olocis/APICheck', 'pdf', null, null, $controls);//Prueba_Juan
        return new Response($report, 200, array(
            'Content-Type' => 'application/pdf'
            )
        );
    }
    /**
     * @Route("/api/lop/report")
     * @Method({"GET"})
     */
    public function getLOPReportAction($id)
    {
        
       $c = new \Jaspersoft\Client\Client("http://127.0.0.1:8081/jasperserver", "jasperadmin", "jasperadmin", "");
        $controls = array("ShipId"=>$id);
        $report = $c->reportService()->runReport('/olocis/Letters', 'pdf', null, null, $controls);//Prueba_Juan
        return new Response($report, 200, array(
            'Content-Type' => 'application/pdf'
            )
        );
    }
    
    /**
     * @Route("/api/linedisp/report")
     * @Method({"GET"})
     */
    public function getLineDispReportAction($id)
    {
       $c = new \Jaspersoft\Client\Client("http://127.0.0.1:8081/jasperserver", "jasperadmin", "jasperadmin", "");
        $controls = array("ShipId"=>$id);
        $report = $c->reportService()->runReport('/olocis/LineDispla', 'pdf', null, null, $controls);//Prueba_Juan
        return new Response($report, 200, array(
            'Content-Type' => 'application/pdf'
            )
        );
    }

    /**
     * @Route("/api/date/search")
     * @Method({"GET"})
     */
    public function getAvailableHoursAction($date, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $dateSelected = \DateTime::createFromFormat('d-m-Y', $date);
        $dateSelected = $dateSelected->format('Y-m-d');
        //dump($this->container, $dateSelected);
        //exit();

        $query = $em->createQuery(
                                    'SELECT c
                                     FROM 
                                            DWDTestAsimovBundle:datesWithDeath c
                                     WHERE 
                                                c.date = :dateVar'
                )->setParameter('dateVar', $dateSelected);

                $timeOccupArrayObjs = $query->getResult();
                
                $workHours = array($dateSelected, "09:00:00", "10:00:00", "11:00:00", "12:00:00", "13:00:00", "14:00:00", "15:00:00", "16:00:00", "17:00:00", "18:00:00");

                if(count($timeOccupArrayObjs) > 0)  {
                    foreach ($timeOccupArrayObjs as $timeOccupArrayObj) {
                        $occupedHours = $timeOccupArrayObj->getTime()->format('H:i:s');

                        $workHours = array_filter($workHours, function ($element) use ($occupedHours) { 
                                                                return ($element != $occupedHours); 
                                                            });
                    }

                    if (count($workHours) > 1) {
                      $ErrorMessage = null;
                    } 
                      else {
                        $ErrorMessage = "There aren't available hours for selected day";
                      }

                    $avaliablesHours = $workHours; 

                } 
                  else {
                    $ErrorMessage = null;
                    $avaliablesHours = $workHours; 
                  }

                

        $response = new Response();

        $response->setContent(json_encode([
            'ErrorMessage' => $ErrorMessage,
            'ResponseData' => $avaliablesHours,
            'Success' => true,
        ]));
        //$response->setContent('Hola Mundo');
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/json');
        // Allow all websites
        $response->headers->set('Access-Control-Allow-Origin', '*');

        // Imprime los headers HTTP seguidos del contenido
        return $response;
        
    }

    /**
     * @Route("/api/date/create")
     * @Method({"POST"})
     */
    public function postCreateDatesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dateData = json_decode($request->getContent()); 
        
        $name = $dateData->{"name"};
        $mail = $dateData->{"mail"};
        $date = \DateTime::createFromFormat('Y-m-d', $dateData->{"date"});
        $hour = \DateTime::createFromFormat('H:i:s', $dateData->{"hour"});
        $dateMnsj = $date->format("d-m-Y");
        $hourMnsj = $hour->format("H:i");
        

        /*
        $name = "juan prueba";
        $mail = "juanprueba@mail.com";
        $date = \DateTime::createFromFormat('Y-m-d', "2018-07-01");
        $hour = \DateTime::createFromFormat('H:i:s', "14:00:00");
        */

        
        $dateWithDeathObj = new datesWithDeath();
        $dateWithDeathObj->setName($name);
        $dateWithDeathObj->setMail($mail);
        $dateWithDeathObj->setDate($date);
        $dateWithDeathObj->setTime($hour);
        
        try {
            $em->persist($dateWithDeathObj);
            $em->flush();
        
            $ErrorMessage = null;
            $dateMnsjSuccess = "On ".$dateMnsj." at ".$hourMnsj." you can dance with death, be on the lookout for any information that may come to your contact email ".$mail;
        } 
            catch (Exception $e) {
            $ErrorMessage = "Appointment not scheduled";
            $dateMnsjSuccess = null;
        }
        
        //dump($this->container, $dateMnsjSuccess);
        //exit();

        $response = new Response();

        $response->setContent(json_encode([
            'ErrorMessage' => $ErrorMessage,
            'ResponseData' => $dateMnsjSuccess,
            'Success' => true,
        ]));
        //$response->setContent('Hola Mundo');
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/json');
        // Allow all websites
        $response->headers->set('Access-Control-Allow-Origin', '*');

        // Imprime los headers HTTP seguidos del contenido
        return $response;
        
    }

}
