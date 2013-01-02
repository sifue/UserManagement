<?php

namespace Sifue\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sifue\Bundle\DomainBundle\Entity\User;
use Sifue\Bundle\UserBundle\Form\UserType;
use Sifue\Bundle\UserBundle\Form\EditUserType;
use Sifue\Bundle\UserBundle\Form\ChangePasswordUserType;

/**
 * User controller.
 *
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SifueDomainBundle:User')->findAll();

        return $this->render('SifueUserBundle:User:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SifueDomainBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SifueUserBundle:User:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays self User entity.
     *
     */
    public function showSelfAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        return $this->redirect($this->generateUrl('user_show', array('id' => $user->getId())));
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function newAction()
    {
        $entity = $this->get('sifue_domain.user_factory')->get();
        $form   = $this->createForm(new UserType(), $entity);

        return $this->render('SifueUserBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * ユーザーのパスワードをエンコードする
     * @param User $entity ユーザーのインスタンス
     */
    private function encodePassword(User $entity)
    {
        $encoderFactory = $this->get('security.encoder_factory');
        $encoder = $encoderFactory->getEncoder($entity);
        $entity->setPassword($encoder->encodePassword($entity->getPassword(), $entity->getSalt()));
    }

    /**
     * Creates a new User entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = $this->get('sifue_domain.user_factory')->get();
        $form = $this->createForm(new UserType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $this->encodePassword($entity);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
        }

        return $this->render('SifueUserBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SifueDomainBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new EditUserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SifueUserBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SifueDomainBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EditUserType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        }

        return $this->render('SifueUserBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SifueDomainBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Displays a form to change password an existing User entity.
     *
     */
    public function changePasswordAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SifueDomainBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new ChangePasswordUserType(), $entity);

        return $this->render('SifueUserBundle:User:change_password.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }

    /**
     * Displays a form to change password an self User entity.
     *
     */
    public function changePasswordSelfAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        return $this->redirect($this->generateUrl('user_change_password', array('id' => $user->getId())));
    }

    /**
     * Update password an existing User entity.
     *
     */
    public function updatePasswordAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SifueDomainBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new ChangePasswordUserType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $this->encodePassword($entity);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_change_password', array('id' => $id)));
        }

        return $this->render('SifueUserBundle:User:change_password.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }

}
