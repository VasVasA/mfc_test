<?php

namespace App\Controller;

use App\Entity\Request as Request;
use App\Form\RequestType;
use App\Form\WorkType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Image;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RequestController extends AbstractController
{
    /**
     * @Route("/", name="request")
     */
    public function index()
    {
        $list = $this->getDoctrine()->getManager();
        $requests = $list->getRepository(Request::class)->findAll();
        return $this->render('request/index.html.twig', [
            'controller_name' => 'RequestController',
            'requests' => $requests
        ]);
    }

    /**
     * @Route("/new-requests", name="new_requests")
     */
    public function newRequests()
    {
        $list = $this->getDoctrine()->getManager();
        $requests = $list->getRepository(Request::class)->findAll();

        $newReq = $this->renderView('_new_requests.html.twig',[
            'requests' => $requests
        ]);

        return new Response($newReq);
    }

    /**
     * @Route("/request/create", name="request_create")
     * @param HttpRequest $httpRequest
     * @return Response
     * @throws Exception
     */

    //изначально выбрал неправильное имя класса,
    //не подумав о классах с такими же названиями, поэтому базовый Request использую как HttpRequest

    public function createAction(HttpRequest $httpRequest){

        $request = new Request();

        $formCreate = $this->createForm(RequestType::class, $request);

        $formCreate ->handleRequest($httpRequest);

        if ($formCreate->isSubmitted() and $formCreate->isValid()){ //валидации нет, проверка на непустое значение
            $request = $formCreate->getData();
            $request->setCreatedAt(new \DateTime('now'));
            $request->setStatus('Новая');


            $manager=$this->getDoctrine()->getManager();
            $manager->persist($request);
            $manager->flush();

            return $this->redirectToRoute('request');
        }

        return $this->render('request/add_form.html.twig', [
            'form' => $formCreate->createView()
        ]);
    }

    /**
     * @Route("/request/{request}", name="request_view")
     * @param Request $request
     * @param HttpRequest $httpRequest
     * @return Response
     * @throws Exception
     */

    public function viewAction(Request $request, HttpRequest $httpRequest){

        $formCreate = $this->createForm(WorkType::class, $request);

        $formCreate ->handleRequest($httpRequest);

        if ($formCreate->isSubmitted() and $formCreate->isValid()){ //валидации нет, проверка на непустое значение
            $request = $formCreate->getData();
            $request->setUpdatedAt(new \DateTime('now'));


            $manager=$this->getDoctrine()->getManager();
            $manager->persist($request);
            $manager->flush();

            return $this->redirectToRoute('request');
        }

        return $this->render('request/request_view.html.twig', [
            'requests' => $request,
            'form' => $formCreate->createView()
        ]);
    }

}
