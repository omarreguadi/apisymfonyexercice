<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

class AdminController extends AbstractFOSRestController
{
    private $articleRepository;
    private $userRepository;
    private $em;

    public function __construct(ArticleRepository $articleRepository, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->articleRepository = $articleRepository;
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @Security(name="api_key"),
     * @SWG\Get(
     *     tags={"Admin"},
     *      @SWG\Response(
     *             response=200,
     *             description="Success",
     *         ),
     *         @SWG\Response(
     *             response=403,
     *             description="Forbiden",
     *         ),
     *         @SWG\Response(
     *             response=401,
     *             description="Unauthorized",
     *         ),
     *)
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/api/admin/users")
     */
    public function getApiUsers()
    {
        $users = $this->userRepository->findAll();
        return $this->view($users);
    }

    /**
     * @Security(name="api_key"),
     * @SWG\Get(
     *     tags={"Admin"},
     *      @SWG\Response(
     *             response=200,
     *             description="Success",
     *         ),
     *         @SWG\Response(
     *             response=403,
     *             description="Forbiden",
     *         ),
     *         @SWG\Response(
     *             response=401,
     *             description="Unauthorized",
     *         ),
     *)
     * @Rest\View(serializerGroups={"article"})
     * @Rest\Get("/api/admin/articles")
     */
    public function getApiArticles()
    {
        $articles = $this->articleRepository->findAll();
        return $this->view($articles);
    }

    // ajouter le reste
}
