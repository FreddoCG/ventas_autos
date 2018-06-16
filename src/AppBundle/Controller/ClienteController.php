<?php
/**
 * Created by PhpStorm.
 * User: freddocg
 */
namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Services\Helpers;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Cliente;

class ClienteController extends Controller
{
    /**
     * @Method({"POST"})
     * @Route("/cliente/agregar", name="agregar_cliente")
     */
    public function nuevoClienteAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json',null);
        $data = array(
            'status'=>'error',
            'code'=>400,
            'msg'=> 'No se pudo registar el cliente'
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


                $nombres =     (isset($params->nombres    )) ? $params->nombres:null;
                $apellidos =  (isset($params->apellidos )) ? $params->apellidos:null;
                $tel1 =    (isset($params->tel1   )) ? $params->tel1:null;
                $tel2 =    (isset($params->tel2   )) ? $params->tel2:null;
                $correo =    (isset($params->correo   )) ? $params->correo:null;
                $direccion =    (isset($params->direccion   )) ? $params->direccion:null;

                if ($correo!==null
                    && $nombres!==null
                    && $apellidos!==null
                    && $tel1!==null
                    && $direccion!==null){

                    $cliente = new Cliente();
                    $cliente->setNombres($nombres);
                    $cliente->setApellidos($apellidos);
                    $cliente->setCorreo($correo);
                    $cliente->setTel1($correo);
                    $cliente->setDireccion($direccion);
                    if ($tel2 !== null)
                        $cliente->setTel2($tel2);
                    $cliente->setStatus('activo');



                    $em = $this->getDoctrine()->getManager();
                    $isset_user = $em->getRepository("BackendBundle:Cliente")->findBy(array(
                        "correo"=>$correo
                    ));

                    if (count($isset_user)==0){
                        $em->persist($cliente);
                        $em->flush();
                        $data = array(
                            'status'=>'success',
                            'code'=>200,
                            'msg'=> 'Cliente creado',
                            'cliente'=> $cliente,

                        );
                    }else{
                        $data = array(
                            'status'=>'error',
                            'code'=>400,
                            'msg'=> 'Cliente duplicado'
                        );
                    }

                }
            }
        } else {
            $data = array(
                'status'=>'error',
                'code'=>400,
                'msg'=>'Authorization no valida!'
            );
        }


        return $helpers->json($data);
    }

    /**
     * @Method({"POST"})
     * @Route("/cliente/editar", name="editar_Cliente")
     */
    public function editClienteAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);

        //id de usuario provicional para autenticacion
        $identity = $request->get('authorization',null);

        $data = array(
            'status'=>'error',
            'code'=>400,
            'msg'=> 'Cliente no actualizado'
        );

        $em = $this->getDoctrine()->getManager();
        $isset_user = $em->getRepository("BackendBundle:Usuario")->findBy(array(
            "id"=>$identity
        ));

        if (count($isset_user)==1){

            //Obtener los datos por POST
            $json = $request->get('json',null);
            $params = json_decode($json);

            if ($json!==null){

                $id_cliente =     (isset($params->id    )) ? $params->id:null;
                //Obtener le objeto a actualizar

                $cliente = $em->getRepository('BackendBundle:Cliente')->findOneBy(array(
                    'id'=>$id_cliente
                ));

                if ($cliente && is_object($cliente)){
                    $nombres =     (isset($params->nombres    )) ? $params->nombres:null;
                    $apellidos =  (isset($params->apellidos )) ? $params->apellidos:null;
                    $tel1 =    (isset($params->tel1   )) ? $params->tel1:null;
                    $tel2 =    (isset($params->tel2   )) ? $params->tel2:null;
                    $correo =    (isset($params->correo   )) ? $params->correo:null;
                    $direccion =    (isset($params->direccion   )) ? $params->direccion:null;

                    if ($correo!==null
                        && $nombres!==null
                        && $apellidos!==null
                        && $tel1!==null
                        && $direccion!==null){
                        $cliente->setNombres($nombres);
                        $cliente->setApellidos($apellidos);
                        $cliente->setCorreo($correo);
                        $cliente->setTel1($correo);
                        $cliente->setDireccion($direccion);
                        if ($tel2 !== null)
                            $cliente->setTel2($tel2);
                        $cliente->setStatus('activo');


                        $isset_client = $em->getRepository("BackendBundle:Cliente")->findBy(array(
                            "correo"=>$correo
                        ));

                        if (count($isset_client)==0){
                            $em->persist($cliente);
                            $em->flush();
                            $data = array(
                                'status'=>'success',
                                'code'=>200,
                                'msg'=> 'Cliente actualizado',
                                'cliente'=> $cliente,

                            );
                        }else{
                            $data = array(
                                'status'=>'error',
                                'code'=>400,
                                'msg'=> 'Cliente no actualizado, correo duplicado'
                            );
                        }
                    }
                } else {
                    $data = array(
                        'status'=>'error',
                        'code'=>404,
                        'msg'=>'Cliente no encontrado'
                    );
                }
            }

        }else{
            $data = array(
                'status'=>'error',
                'code'=>400,
                'msg'=>'Authorization no valida!'
            );
        }

        return $helpers->json($data);
    }


    /**
     * @Method({"POST"})
     * @Route("/cliente/listar", name="obtener_cliente")
     */
    public function listarClienteAction(Request $request)
    {
        $helps = $this->get(Helpers::class);
        $identity = $request->get('authorization',null);

        $em = $this->getDoctrine()->getManager();
        $isset_user = $em->getRepository("BackendBundle:Usuario")->findBy(array(
            "id"=>$identity
        ));

        if (count($isset_user)==1) {
            $em = $this->getDoctrine()->getManager();
            $clientRepo = $em->getRepository('BackendBundle:Cliente');
            $clientes = $clientRepo->findAll();

            $data = array("status" => "OK", "data" => $clientes);
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
     * @Route("/cliente/eliminar", name="eliminar_cliente")
     */
    public function removeClienteAction(Request $request){
        $helpers = $this->get(Helpers::class);

        $identity = $request->get('authorization',null);
        $id_cliente = $request->get('id_cliente',null);

        $em = $this->getDoctrine()->getManager();
        $isset_user = $em->getRepository("BackendBundle:Usuario")->findBy(array(
            "id"=>$identity
        ));

        if (count($isset_user)==1){

            $cliente = $em->getRepository('BackendBundle:Cliente')->findOneBy(array(
                'id'=>$id_cliente
            ));

            if ($cliente && is_object($cliente)){
                $em->remove($cliente);
                $em->flush();
                $data = array(
                    'status'=>'success',
                    'code'=>200,
                    'data'=>$cliente
                );
            }else{
                $data = array(
                    'status'=>'error',
                    'code'=>404,
                    'msg'=>'Cliente no encontrado'
                );
            }
        }else{
            $data = array(
                'status'=>'error',
                'code'=>400,
                'msg'=>'Autorizacion no valida'
            );
        }

        return $helpers->json($data);

    }

}