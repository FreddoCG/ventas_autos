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
use BackendBundle\Entity\Auto;

class AutoController extends Controller
{
    /**
     * @Method({"POST"})
     * @Route("/auto/agregar", name="agregar_auto")
     */
    public function nuevoAutoAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json',null);
        $data = array(
            'status'=>'error',
            'code'=>400,
            'msg'=> 'No se pudo registar el auto'
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


                $marca =     (isset($params->marca    )) ? $params->marca:null;
                $modelo =  (isset($params->modelo )) ? $params->modelo:null;
                $anio =    (isset($params->anio   )) ? $params->anio:null;
                $precio =    (isset($params->precio   )) ? $params->precio:null;
                $descripcion =    (isset($params->descripcion   )) ? $params->descripcion:null;
                $status =    'disponible';
                $fecha_registro = new  \DateTime("now");

                if ($marca!==null
                    && $modelo!==null
                    && $anio!==null
                    && $descripcion!==null){

                    $auto = new Auto();
                    $auto->setMarca($marca);
                    $auto->setModelo($modelo);
                    $auto->setAnio($anio);
                    $auto->setPrecio($precio);
                    $auto->setDescripcion($descripcion);
                    $auto->setStatus($status);
                    $auto->setFechaRegistro($fecha_registro);

                    $em->persist($auto);
                    $em->flush();
                    $data = array(
                        'status'=>'success',
                        'code'=>200,
                        'msg'=> 'Auto creado',
                        'auto'=> $auto,

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
     * @Route("/auto/editar", name="editar_Auto")
     */
    public function editAutoAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);

        //id de usuario provicional para autenticacion
        $identity = $request->get('authorization',null);

        $data = array(
            'status'=>'error',
            'code'=>400,
            'msg'=> 'Auto no actualizado'
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
                $id_auto =     (isset($params->id    )) ? $params->id:null;
                //Obtener le objeto a actualizar
                $auto = $em->getRepository('BackendBundle:Auto')->findOneBy(array(
                    'id'=>$id_auto
                ));

                if ($auto && is_object($auto)){
                    $marca =     (isset($params->marca    )) ? $params->marca:null;
                    $modelo =  (isset($params->modelo )) ? $params->modelo:null;
                    $anio =    (isset($params->anio   )) ? $params->anio:null;
                    $precio =    (isset($params->precio   )) ? $params->precio:null;
                    $descripcion =    (isset($params->descripcion   )) ? $params->descripcion:null;
                    $status =    (isset($params->status   )) ? $params->status:null;

                    if ($marca!==null
                        && $modelo!==null
                        && $anio!==null
                        && $descripcion!==null){

                        $auto->setMarca($marca);
                        $auto->setModelo($modelo);
                        $auto->setAnio($anio);
                        $auto->setPrecio($precio);
                        $auto->setDescripcion($descripcion);
                        $auto->setStatus($status);

                        $em->persist($auto);
                        $em->flush();
                        $data = array(
                            'status'=>'success',
                            'code'=>200,
                            'msg'=> 'Auto actualizado',
                            'auto'=> $auto,

                        );
                    }

                }else{
                    $data = array(
                        'status'=>'error',
                        'code'=>404,
                        'msg'=>'Auto no encontrado'
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
     * @Route("/auto/listar", name="obtener_auto")
     */
    public function listarAutoAction(Request $request)
    {
        $helps = $this->get(Helpers::class);
        $identity = $request->get('authorization',null);

        $em = $this->getDoctrine()->getManager();
        $isset_user = $em->getRepository("BackendBundle:Usuario")->findBy(array(
            "id"=>$identity
        ));

        if (count($isset_user)==1) {
            $em = $this->getDoctrine()->getManager();
            $clientRepo = $em->getRepository('BackendBundle:Auto');
            $autos = $clientRepo->findAll();

            $data = array("status" => "OK", "data" => $autos);
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
     * @Route("/auto/eliminar", name="eliminar_auto")
     */
    public function removeAutoAction(Request $request){
        $helpers = $this->get(Helpers::class);

        $identity = $request->get('authorization',null);
        $id_auto = $request->get('id_auto',null);

        $em = $this->getDoctrine()->getManager();
        $isset_user = $em->getRepository("BackendBundle:Usuario")->findBy(array(
            "id"=>$identity
        ));

        if (count($isset_user)==1){

            $auto = $em->getRepository('BackendBundle:Auto')->findOneBy(array(
                'id'=>$id_auto
            ));

            if ($auto && is_object($auto)){
                $em->remove($auto);
                $em->flush();
                $data = array(
                    'status'=>'success',
                    'code'=>200,
                    'data'=>$auto
                );
            }else{
                $data = array(
                    'status'=>'error',
                    'code'=>404,
                    'msg'=>'Auto no encontrado'
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