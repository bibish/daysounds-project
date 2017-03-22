<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        // all datas to push on view
        $datas = [];

        // Block New Users
        $datas["newUsers"] = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findNewUsers();

        return $this->render('ProjectBundle:Default:index.html.twig', array("datas" => $datas));
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function playlistsAction()
    {
        $repository = $this->getDoctrine()->getRepository('ProjectBundle:Playlist');

        $listePlaylist = $repository->findAll();

        return $this->render('ProjectBundle:Default:profil.html.twig',  ["listePlaylist" => $listePlaylist]);
    }

    /**
     * @Route("/playlist/{id}")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function seePlaylist($id)
    {
        return $this->render('ProjectBundle:Default:profil.html.twig', ["id" => $id]);
    }

    /**
     * @Route("/users/{slug_username}", name="day_sounds")
     * @param $slug_username
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function renderDayliAction($slug_username)
    {
        $user = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findBySlug($slug_username);

        if(!count($user)){
            return $this->redirect($this->generateUrl('404'));
        }

        $repository = $this->getDoctrine()->getRepository('ProjectBundle:Playlist');
        $listePlaylist = $repository->findByUser($user);

        return $this->render('ProjectBundle:Default:daysoundPlaylist.html.twig');
    }

    // Afficher le profil d'un utilisateur avec toutes ses playlists

    /**
     * @Route("/users/{slug_username}/musiques", name="mon_profil")
     * @param $slug_username
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function renderProfilAction($slug_username)
    {
        $user = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findBySlug($slug_username);
        if(!count($user)){
            return $this->redirect($this->generateUrl('404'));
        }

        $repository = $this->getDoctrine()->getRepository('ProjectBundle:Playlist');
        $listePlaylist = $repository->findByUser($user);

        return $this->render('ProjectBundle:Default:profil.html.twig',
            ["listePlaylist" => $listePlaylist, "id_user" => $user, "slug_username"=>$slug_username]);
    }


    /**
     * @Route("/page-not-found", name="404")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function render404Action()
    {
        return $this->render('ProjectBundle:Default:404.html.twig');
    }

}
