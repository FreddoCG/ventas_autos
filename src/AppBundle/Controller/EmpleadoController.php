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
use BackendBundle\Entity\Empleado;

class EmpleadoController extends Controller
{
    /**
     * @Method({"POST"})
     * @Route("/empleado/agregar", name="agregar_empleado")
     */
    public function nuevoEmpleadoAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json',null);
        $data = array(
            'status'=>'error',
            'code'=>400,
            'msg'=> 'No se pudo registar el empleado'
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


                $nombres =    (isset($params->nombres    )) ? $params->nombres:null;
                $apellidos =  (isset($params->apellidos )) ? $params->apellidos:null;
                $correo =     (isset($params->correo   )) ? $params->correo:null;
                $tel =        (isset($params->tel   )) ? $params->tel:null;
                $fecha_inicio = new  \DateTime("now");

                if ($nombres!==null
                    && $apellidos!==null
                    && $correo!==null
                    && $tel!==null){

                    $empleado = new Empleado();
                    $empleado->setNombres($nombres);
                    $empleado->setApellidos($apellidos);
                    $empleado->setCorreo($correo);
                    $empleado->setTel($tel);
                    $empleado->setFechaInicio($fecha_inicio);
                    $empleado->setFechaFin(null);
                    $empleado->setStatus('activo');

                    $em->persist($empleado);
                    $em->flush();
                    $data = array(
                        'status'=>'success',
                        'code'=>200,
                        'msg'=> 'Empleado creado',
                        'empleado'=> $empleado,

                    );

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
     * @Route("/empleado/editar", name="editar_Empleado")
     */
    public function editEmpleadoAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);

        $identity = $request->get('authorization',null);

        $data = array(
            'status'=>'error',
            'code'=>400,
            'msg'=> 'Empleado no actualizado'
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
                $id_empleado =     (isset($params->id    )) ? $params->id:null;
                //Obtener le objeto a actualizar
                $empleado = $em->getRepository('BackendBundle:Empleado')->findOneBy(array(
                    'id'=>$id_empleado
                ));

                if ($empleado && is_object($empleado)){

                    $nombres =    (isset($params->nombres    )) ? $params->nombres:null;
                    $apellidos =  (isset($params->apellidos )) ? $params->apellidos:null;
                    $correo =     (isset($params->correo   )) ? $params->correo:null;
                    $tel =        (isset($params->tel   )) ? $params->tel:null;
                    $fecha_fin =        (isset($params->fecha_fin   )) ? $params->fecha_fin:null;
                    $status =        (isset($params->status   )) ? $params->status:null;

                    if ($nombres!==null
                        && $apellidos!==null
                        && $tel!==null){
                        $empleado->setNombres($nombres);
                        $empleado->setApellidos($apellidos);
                        $empleado->setTel($tel);
                        if ($correo!==null){
                            $empleado->setCorreo($correo);
                        }
                        if ($fecha_fin!==null){
                            $empleado->setFechaFin($fecha_fin);
                        }
                        if ($status!==null){
                            $empleado->setStatus($status);
                        }



                        $isset_client = $em->getRepository("BackendBundle:Empleado")->findBy(array(
                            "correo"=>$correo
                        ));

                        if (count($isset_client)==0){
                        $em->persist($empleado);
                        $em->flush();
                            $data = array(
                                'status'=>'success',
                                'code'=>200,
                                'msg'=> 'Empleado actualizado',
                                'empleado'=> $empleado
                            );
                        }else{
                            $data = array(
                                'status'=>'error',
                                'code'=>400,
                                'msg'=> 'Empleado no actualizado, correo duplicado'
                            );
                        }
                    }

                }else{
                    $data = array(
                        'status'=>'error',
                        'code'=>404,
                        'msg'=>'Empleado no encontrado'
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
     * @Route("/empleado/listar", name="obtener_empleado")
     */
    public function listarEmpleadoAction(Request $request)
    {
        $helps = $this->get(Helpers::class);
        $identity = $request->get('authorization',null);

        $em = $this->getDoctrine()->getManager();
        $isset_user = $em->getRepository("BackendBundle:Usuario")->findBy(array(
            "id"=>$identity
        ));

        if (count($isset_user)==1) {
            $em = $this->getDoctrine()->getManager();
            $clientRepo = $em->getRepository('BackendBundle:Empleado');
            $empleados = $clientRepo->findAll();

            $data = array("status" => "OK", "data" => $empleados);
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
     * @Route("/empleado/eliminar", name="eliminar_empleado")
     */
    public function removeEmpleadoAction(Request $request){
        $helpers = $this->get(Helpers::class);

        $identity = $request->get('authorization',null);
        $id_empleado = $request->get('id_empleado',null);

        $em = $this->getDoctrine()->getManager();
        $isset_user = $em->getRepository("BackendBundle:Usuario")->findBy(array(
            "id"=>$identity
        ));

        if (count($isset_user)==1){

            $empleado = $em->getRepository('BackendBundle:Empleado')->findOneBy(array(
                'id'=>$id_empleado
            ));

            if ($empleado && is_object($empleado)){
                $em->remove($empleado);
                $em->flush();
                $data = array(
                    'status'=>'success',
                    'code'=>200,
                    'data'=>$empleado
                );
            }else{
                $data = array(
                    'status'=>'error',
                    'code'=>404,
                    'msg'=>'Empleado no encontrado'
                );
            }
        }else{
            $data = array(
                'status'=>'error',
                'code'=>400,
                'msg'=>'Empleadorizacion no valida'
            );
        }

        return $helpers->json($data);

    }

}