<?php
namespace App\Controller;

use App\Entity\Provincia;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProvinciaType;

class ProvinciaController extends AbstractController{

    /**
    * @Route("/provincia/nuevo", name="provincia_nueva")
    */

    public function nuevo(ManagerRegistry $doctrine, Request $request) {
        $provincia = new Provincia();

        $formulario = $this->createForm(ProvinciaType::class, $provincia);
        
        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $provincia = $formulario->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($provincia);
            $entityManager->flush();
            return $this->redirectToRoute('lista_provincia');
        }

        return $this->render('nuevo.html.twig', array(
            'formulario' => $formulario->createView()
        ));
    }

    /**
     * @Route("/provincia/lista", name="lista_provincia")]
     */
    public function buscar(ManagerRegistry $doctrine): Response{
        
        $repositorio = $doctrine->getRepository(Provincia::class);

        $provincias = $repositorio->findAll();

        return $this->render('lista_provincia.html.twig', [
            'provincias' => $provincias
        ]);
    }
    
    /**
     * @Route("/provincia/delete/{id}", name="eliminar_provincia")
     */
    public function delete(ManagerRegistry $doctrine, $id): Response{
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Provincia::class);
        $provincia = $repositorio->find($id);
        if($provincia){
            try{
                $entityManager->remove($provincia);
                $entityManager->flush();
                return $this->redirectToRoute('lista_provincia');
            }catch (\Exception $e){
                return new Response("Error eliminar objeto");
            }
        }else{
            return $this->render('lista_provincia.html.twig' , [
                'provincia' => null
            ]);
        }
    }

}
?>