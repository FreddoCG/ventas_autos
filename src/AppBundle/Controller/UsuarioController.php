<?php
/**
 * Created by PhpStorm.
 * User: freddocg
 */
namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Services\Helpers;
use BackendBundle\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class UsuarioController extends Controller
{
    /**
     * @Method({"POST"})
     * @Route("/usuario/agregar", name="agregar_usuario")
     */
    public function newAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json',null);
        $data = array(
            'status'=>'error',
            'code'=>400,
            'msg'=> 'No se pudo regsitar el usuario!'
        );
        $params = json_decode($json);
        if ($json!==null){
            $createdAt= new  \DateTime("now");
            $nombres =     (isset($params->nombres    )) ? $params->nombres:null;
            $apellidos =  (isset($params->apellidos )) ? $params->apellidos:null;
            $correo =    (isset($params->correo   )) ? $params->correo:null;
            $contrasena = (isset($params->contrasena)) ? $params->contrasena:null;

            if ($correo!==null
                && $contrasena!==null
                && $nombres!==null
                && $apellidos!==null){

                $user = new Usuario();
                $user->setCreado($createdAt);
                $user->setNombres($nombres);
                $user->setApellidos($apellidos);
                $user->setCorreo($correo);
                if ($contrasena!==null){
                    $pwrd = hash('sha256',$contrasena);
                    $user->setContrasena($pwrd);
                }

                $em = $this->getDoctrine()->getManager();
                $isset_user = $em->getRepository("BackendBundle:Usuario")->findBy(array(
                    "correo"=>$correo
                ));

                if (count($isset_user)==0){
                    $em->persist($user);
                    $em->flush();
                    $data = array(
                        'status'=>'success',
                        'code'=>200,
                        'msg'=> 'Usuario creado',
                        'user'=> $user,

                    );
                }else{
                    $data = array(
                        'status'=>'error',
                        'code'=>400,
                        'msg'=> 'Usuario duplicado'
                    );
                }
            }
        }

        return $helpers->json($data);
    }

    /**
     * @Method({"POST"})
     * @Route("/usuario/editar", name="editar_usuario")
     */
    public function editAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);

        //id de usuario provicional para autenticacion
        $identity = $request->get('authorization',null);

        $data = array(
            'status'=>'error',
            'code'=>400,
            'msg'=> 'Usuario no actualizado'
        );

        $em = $this->getDoctrine()->getManager();
        $isset_user = $em->getRepository("BackendBundle:Usuario")->findBy(array(
            "id"=>$identity
        ));

        if (count($isset_user)==1){
            //Obtener le objeto a actualizar
            $user = $em->getRepository('BackendBundle:Usuario')->findOneBy(array(
                'id'=>$identity
            ));
            //Obtener los datos por POST
            $json = $request->get('json',null);
            $params = json_decode($json);

            if ($json!==null){
                $name =     (isset($params->nombres    )) ? $params->nombres:null;
                $subname =  (isset($params->apellidos )) ? $params->apellidos:null;
                $email =    (isset($params->correo   )) ? $params->correo:null;
                $password = (isset($params->contrasena   )) ? $params->contrasena:null;

                if ($email!==null
                    && $subname!==null
                    && $name!==null){

                    $user->setNombres($name);
                    $user->setApellidos($subname);
                    $user->setCorreo($email);
                    if ($password!==null){
                        $pwrd = hash('sha256',$password);
                        $user->setContrasena($pwrd);
                    }


                    $isset_user = $em->getRepository("BackendBundle:Usuario")->findBy(array(
                        "correo"=>$email
                    ));

                    if (count($isset_user)==0){
                        $em->persist($user);
                        $em->flush();
                        $data = array(
                            'status'=>'success',
                            'code'=>200,
                            'msg'=> 'Usuario actualizado',
                            'user'=> $user,

                        );
                    }else{
                        $data = array(
                            'status'=>'error',
                            'code'=>400,
                            'msg'=> 'Usuario no actualizado, correo duplicado'
                        );
                    }
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

}