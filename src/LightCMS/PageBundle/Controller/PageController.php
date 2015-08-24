<?php

namespace LightCMS\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use LightCMS\PageBundle\Entity\Page;
use LightCMS\PageBundle\Entity\Version;

class PageController extends Controller
{

    public function viewAction($param)
    {

    }

    public function createAction(Request $request, $params)
    {
        $entity = new Page();

        return $this->formAction($request, $entity, 'create');
    }


    public function editAction(Request $request, $params)
    {
        $entity = $this->getDoctrine()->getRepository('LightCMSPageBundle:Page')->find($params['id']);

        return $this->formAction($request, $entity, 'edit');
    }

    public function formAction(Request $request, $entity, $action)
    {
        if (is_null($entity)) {
            return null;
        }

        $form = $this->createForm('page', $entity, array(
            'action' => $request->getUri(),
            'method' => 'POST'
        ));

        $form->handleRequest($request);

        $redirect = false;

        if ($form->isValid()) {

            if ($form->get('submit')->isClicked()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $redirect = true;
            }

        }

        if ($redirect) {
            $lcmsUrl = $this->get('light_cms_core.service.generate_url');
            return $this->redirect($lcmsUrl->generateUrl('node', 'windgetcontent', 'edit', array(
                'id' => $entity->getId()
            )));
        }

        $entities = $this->getDoctrine()->getRepository('LightCMSPageBundle:Node')->findBy(
            array('parent' => null),
            array('name' => 'ASC')
        );

        return $this->render('LightCMSPageBundle:Page:edit.html.twig', array(
            'form' => $form->createView(),
            'entities' => $entities,
            'entity' => $entity
        ));
    }

    public function parentEntityAction(Request $request, $params)
    {
        $entities = $this->getDoctrine()->getRepository('LightCMSPageBundle:Node')->findBy(array('parent' => null));

        return $this->render('LightCMSPageBundle:Page:parent_entity.html.twig', array(
            'entities' => $entities,
            'id' => $params['id']
        ));
    }

}

?>