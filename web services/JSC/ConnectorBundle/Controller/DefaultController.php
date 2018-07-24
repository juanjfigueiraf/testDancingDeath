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
