<?php
/**
 * Created by PhpStorm.
 * User: freddocg
 */
namespace AppBundle\Controller;

//php bin/console doctrine:generate:entities BackendBundle
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Services\Helpers;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Venta;

class VentaController extends Controller
{
    /**
     * @Method({"POST"})
     * @Route("/venta/agregar", name="agregar_venta")
     */
    public function nuevoVentaAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json',null);
        $data = array(
            'status'=>'error',
            'code'=>400,
            'msg'=> 'No se pudo registar la venta'
        );
        //id de usuario provicional para autenticacion
        $identity = $request->get('authorization',null);

        $em = $this->getDoctrine()->getManager();
        $isset_user = $em->getRepository("BackendBundle:Usuario")->findBy(array(
            "id"=>$identity
        ));

        if (count($isset_user)==1){
            $params = json_decode($json);
            if ($json!==null){


                $cliente_id =    (isset($params->cliente_id    )) ? $params->cliente_id:null;
                $empleado_id =  (isset($params->empleado_id )) ? $params->empleado_id:null;
                $auto_id =     (isset($params->auto_id   )) ? $params->auto_id:null;
                $observacion =        (isset($params->observacion   )) ? $params->observacion:null;
                $fecha_venta = new  \DateTime("now");

                if ($cliente_id!==null
                    && $empleado_id!==null
                    && $auto_id!==null){

                    //Obtener los datos del cliente
                    $cliente = $em->getRepository('BackendBundle:Cliente')->findOneBy(array(
                        'id'=>$cliente_id
                    ));


                    if (!$cliente && !is_object($cliente)){
                        $cliente = null;
                    }

                    //Obtener los datos del empleado
                    $empleado= $em->getRepository('BackendBundle:Empleado')->findOneBy(array(
                        'id'=>$empleado_id
                    ));
                    if (!$empleado && !is_object($empleado)){
                        $empleado = null;
                    }

                    /*var_dump($empleado);
                    die();*/
                    //Obtener los datos del auto a vender
                    $auto= $em->getRepository('BackendBundle:Auto')->findOneBy(array(
                        'id'=>$auto_id
                    ));
                    if (!$auto && !is_object($auto)){
                        $auto = null;
                    }
                    //validar que el auto a vender exista en stock
                    $rwq = "SELECT * FROM venta WHERE auto_id= $auto_id";

                    $statement = $em->getConnection()->prepare($rwq);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    if (count($result)==0){
                        if ($cliente!=null && $empleado!=null && $auto!=null){

                            $venta = new Venta();
                            $venta->setCliente($cliente);
                            $venta->setEmpleado($empleado);
                            $venta->setAuto($auto);
                            if ($observacion!=null){
                                $venta->setObservacion($observacion);
                            }
                            $venta->setFechaVenta($fecha_venta);

                            $auto->setStatus('vendido');

                            $em->persist($auto);
                            $em->flush();

                            $em->persist($venta);
                            $em->flush();
                            $data = array(
                                'status'=>'success',
                                'code'=>200,
                                'msg'=> 'Venta creada',
                                'venta'=> $venta,

                            );
                        }else{
                            $data = array(
                                'status'=>'error',
                                'code'=>400,
                                'msg'=>'No existe '.($cliente==null?"Cliente ":"").($empleado==null?"Empleado ":"").($auto==null?"Auto ":"")
                            );
                        }
                    }else {
                        $data = array(
                            'status'=>'error',
                            'code'=>400,
                            'msg'=>'Este auto ha sido vendido con anterioridad'
                        );
                    }

                }
            }
        } else {
            $data = array(
                'status'=>'error',
                'code'=>400,
                'msg'=>'Autorization no valida!'
            );
        }


        return $helpers->json($data);
    }

    /**
     * @Method({"POST"})
     * @Route("/venta/editar", name="editar_venta")
     */
    public function editVentaAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json',null);
        $data = array(
            'status'=>'error',
            'code'=>400,
            'msg'=> 'No se pudo actualizar la venta'
        );
        //id de usuario provicional para autenticacion
        $identity = $request->get('authorization',null);

        $em = $this->getDoctrine()->getManager();
        $isset_user = $em->getRepository("BackendBundle:Usuario")->findBy(array(
            "id"=>$identity
        ));

        if (count($isset_user)==1){
            $params = json_decode($json);
            if ($json!==null){


                $id_venta =    (isset($params->id    )) ? $params->id:null;
                $cliente_id =    (isset($params->cliente_id    )) ? $params->cliente_id:null;
                $empleado_id =  (isset($params->empleado_id )) ? $params->empleado_id:null;
                $auto_id =     (isset($params->auto_id   )) ? $params->auto_id:null;
                $observacion =        (isset($params->observacion   )) ? $params->observacion:null;
                $fecha_venta = new  \DateTime("now");

                if ($id_venta!==null
                    && $cliente_id!==null
                    && $empleado_id!==null
                    && $auto_id!==null){

                    //Obtener los datos del cliente
                    $cliente = $em->getRepository('BackendBundle:Cliente')->findOneBy(array(
                        'id'=>$cliente_id
                    ));


                    if (!$cliente && !is_object($cliente)){
                        $cliente = null;
                    }

                    //Obtener los datos del empleado
                    $empleado= $em->getRepository('BackendBundle:Empleado')->findOneBy(array(
                        'id'=>$empleado_id
                    ));
                    if (!$empleado && !is_object($empleado)){
                        $empleado = null;
                    }

                    /*var_dump($empleado);
                    die();*/
                    //Obtener los datos del auto a vender
                    $auto= $em->getRepository('BackendBundle:Auto')->findOneBy(array(
                        'id'=>$auto_id
                    ));
                    if (!$auto && !is_object($auto)){
                        $auto = null;
                    }
                    //validar que el auto a vender exista en stock
                    $rwq = "SELECT * FROM venta WHERE auto_id= $auto_id";

                    $statement = $em->getConnection()->prepare($rwq);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    if (count($result)==0){
                        if ($cliente!=null && $empleado!=null && $auto!=null){

                            $venta = $em->getRepository('BackendBundle:Venta')->findOneBy(array(
                                'id'=>$id_venta
                            ));

                            if ($venta && is_object($venta)){
                                //actualizar el status del auto anterior
                                $auto2 = $venta->getAuto();
                                $auto2->setStatus('disponible');

                                $em->persist($auto2);
                                $em->flush();

                                $venta->setCliente($cliente);
                                $venta->setEmpleado($empleado);
                                $venta->setAuto($auto);
                                if ($observacion!=null){
                                    $venta->setObservacion($observacion);
                                }
                                $venta->setFechaVenta($fecha_venta);

                                $auto->setStatus('vendido');

                                $em->persist($auto);
                                $em->flush();



                                $em->persist($venta);
                                $em->flush();
                                $data = array(
                                    'status'=>'success',
                                    'code'=>200,
                                    'msg'=> 'Venta actualizada',
                                    'venta'=> $venta,

                                );
                            }else{
                                $data = array(
                                    'status'=>'error',
                                    'code'=>404,
                                    'msg'=>'Venta no encontrado'
                                );
                            }

                        }else{
                            $data = array(
                                'status'=>'error',
                                'code'=>400,
                                'msg'=>'No existe '.($cliente==null?"Cliente ":"").($empleado==null?"Empleado ":"").($auto==null?"Auto ":"")
                            );
                        }
                    }else {
                        $data = array(
                            'status'=>'error',
                            'code'=>400,
                            'msg'=>'Este auto ha sido vendido con anterioridad'
                        );
                    }

                }
            }
        } else {
            $data = array(
                'status'=>'error',
                'code'=>400,
                'msg'=>'Autorization no valida!'
            );
        }


        return $helpers->json($data);
    }

    /**
     * @Method({"POST"})
     * @Route("/venta/listar", name="obtener_venta")
     */
    public function listarVentaAction(Request $request)
    {
        $helps = $this->get(Helpers::class);
        $identity = $request->get('authorization',null);

        $em = $this->getDoctrine()->getManager();
        $isset_user = $em->getRepository("BackendBundle:Usuario")->findBy(array(
            "id"=>$identity
        ));

        if (count($isset_user)==1) {
            $em = $this->getDoctrine()->getManager();
            $clientRepo = $em->getRepository('BackendBundle:Venta');
            $ventas = $clientRepo->findAll();

            $data = array("status" => "OK", "data" => $ventas);
        } else {
            $data = array(
                'status'=>'error',
                'code'=>400,
                'msg'=>'Authorization no valida!'
            );
        }
        return $helps->json($data);
    }


    /**
     * @Method({"POST"})
     * @Route("/venta/eliminar", name="eliminar_venta")
     */
    public function removeVentaAction(Request $request){
        $helpers = $this->get(Helpers::class);

        $identity = $request->get('authorization',null);
        $id_venta = $request->get('id_venta',null);

        $em = $this->getDoctrine()->getManager();
        $isset_user = $em->getRepository("BackendBundle:Usuario")->findBy(array(
            "id"=>$identity
        ));

        if (count($isset_user)==1){

            $venta = $em->getRepository('BackendBundle:Venta')->findOneBy(array(
                'id'=>$id_venta
            ));

            if ($venta && is_object($venta)){

                $auto = $venta->getAuto();

                $auto->setStatus('disponible');
                $em->persist($auto);
                $em->flush();

                $em->remove($venta);
                $em->flush();

                $data = array(
                    'status'=>'success',
                    'code'=>200,
                    'data'=>$venta
                );
            }else{
                $data = array(
                    'status'=>'error',
                    'code'=>404,
                    'msg'=>'Venta no encontrada'
                );
            }
        }else{
            $data = array(
                'status'=>'error',
                'code'=>400,
                'msg'=>'Ventarizacion no valida'
            );
        }

        return $helpers->json($data);

    }

}