<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Swagger\Annotations as SWG;


class ArticleController extends AbstractFOSRestController
{
    private $articleRepository;
    private $em;

    public function __construct(ArticleRepository $articleRepository, EntityManagerInterface $em)
    {
        $this->articleRepository = $articleRepository;
        $this->em = $em;
    }

    /**
     * @Security(name="api_key"),
     * @SWG\Get(
     *     tags={"Article"},
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
     * @
     * Rest\View(serializerGroups={"article"})
     * @Rest\Get("/api/articles/{id}")
     */
    public function getApiArticle(Article $article)
    {
        return $this->view($article);
    }

    /**
     * @Security(name="api_key"),
     * @SWG\Get(
     *     tags={"Article"},
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
     * @Rest\Get("/api/articles")
     */
    public function getApiArticles()
    {
        $articles = $this->articleRepository->findAll();
        return $this->view($articles);
    }

    /**
     * @Security(name="api_key"),
     * @SWG\Post(
     *     tags={"Article"},
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
     * @Rest\Post("/api/articles")
     * @ParamConverter("article", converter="fos_rest.request_body")
     */
    public function postApiArticle(Article $article)
    {
        $article->setUser($this->getUser()); // ajoute l'id du user en mettant X-AUTH-TOKEN et le api_key dans PostMan HEADER
        $this->em->persist($article);
        $this->em->flush();
        return $this->view($article);
    }

    /**
     * @Security(name="api_key"),
     * @SWG\Patch(
     *     tags={"Article"},
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
     * @Rest\Patch("/api/articles/{id}")
     */
    public function patchApiArticle(Article $article, Request $request)
    {
        if ($this->getUser()->getArticles()->contains($article)) {

            $name = $request->get('name');
            $created_at = $request->get('created_at');
            $description = $request->get('description');

            if ($name !== null) {
                $article->setName($name);
            }
            if ($created_at !== null) {
                $article->setCreatedAt($created_at);
            }
            if ($description !== null) {
                $article->setDescription($description);
            }

            $this->em->persist($article);
            $this->em->flush();
            return $this->view($article);
        }
    }

    /**
     * @Security(name="api_key"),
     * @SWG\Delete(
     *     tags={"Article"},
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
     * @Rest\Delete("/api/articles/{id}")
     */
    public function deleteApiArticle(Article $article)
    {
        if ($this->getUser()->getArticles()->contains($article)) { //compare les id article et user

            $this->em->remove($article);
            $this->em->flush();

            dd("Article supprimé");

        } else {
            throw new BadRequestHttpException(); //affiche message d'erreur
        }
    }
}
