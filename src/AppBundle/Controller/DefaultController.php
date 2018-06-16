<?php
/**
 * Created by PhpStorm.
 * User: freddocg
 */
namespace AppBundle\Controller;

use AppBundle\Services\Helpers;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/holamundo", name="holamundo")
     * @Method({"GET"})
     */
    public function holamundoAction(Request $request)
    {
        $re = array("status"=>"OK","data"=>"Hola Mundo!");
        $response = new Response(json_encode($re));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Method({"GET"})
     * @Route("/prueba", name="prueba")
     */
    public function pruebaAction(Request $request)
    {
        $helps = $this->get(Helpers::class);
        $em = $this->getDoctrine()->getManager();
        $clientRepo = $em->getRepository('BackendBundle:Cliente');
        $clientes = $clientRepo->findAll();

        $re = array("status"=>"OK","data"=>$clientes);
        return $helps->json($re);
    }
}
