<?php

namespace LightCMS\NodeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use LightCMS\NodeBundle\Entity\Folder;

class FolderController extends Controller
{
    public function viewAction($site, $node)
    {
        return $this->render('LightCMSNodeBundle:Folder:view.html.twig', array(
            'site' => $site,
            'node' => $node
        ));
    }

    public function editAction(Request $request, $id)
    {
        if ($id == 'new') {
            $folder = new Folder();
        } else {
            $folder = $this->getDoctrine()->getRepository('LightCMSNodeBundle:Node')->find($id);
        }

        // Form creation
        $form = $this->createForm('folder', $folder, array(
            'action' => $request->getUri(),
            'method' => 'POST'
        ));

        $form->handleRequest($request);

        if ($form->get('actionup')->get('submit')->isClicked() or $form->get('actiondown')->get('submit')->isClicked()) {

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();;
                $em->persist($folder);
                $em->flush();

            }
        }

        return $this->render('LightCMSNodeBundle:Folder:edit.html.twig', array(
            'site' => $folder,
            'form' => $form->createView()));
    }

}
