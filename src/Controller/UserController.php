<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractFOSRestController
{
    private $userRepository;
    private $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @Security(name="api_key"),
     * @SWG\Get(
     *     tags={"User"},
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
     * @Rest\Get("/api/users/{id}")
     */
    public function getApiUser(User $user)
    {
        return $this->view($user);
    }

    /**
     * @Security(name="api_key"),
     * @SWG\Get(
     *     tags={"User"},
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
     * @Rest\Get("/api/users")
     */
    public function getApiUsers()
    {
        $users = $this->userRepository->findAll();
        return $this->view($users);
    }

    /**
     * @Security(name="api_key"),
     * @SWG\Post(
     *     tags={"User"},
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
     * @Rest\Post("/api/users")
     * @return \FOS\RestBundle\View\View
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function postApiUser(User $user, ConstraintViolationListInterface $validationErrors)
    {
        $errors = array();
        if ($validationErrors->count() > 0) {
            /** @var ConstraintViolation $constraintViolation */
            foreach ($validationErrors as $constraintViolation) {
                // Returns the violation message. (Ex. This value should not be blank.)
                $message = $constraintViolation->getMessage();
                // Returns the property path from the root element to the violation. (Ex. lastname)
                $propertyPath = $constraintViolation->getPropertyPath();
                $errors[] = ['message' => $message, 'propertyPath' => $propertyPath];
            }
        }
        if (!empty($errors)) {
            // Throw a 400 Bad Request with all errors messages (Not readable, you can do better)
            throw new BadRequestHttpException(\json_encode($errors));
        }

        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user, 201);

//        $this->em->persist($user);
//        $this->em->flush();
//        return $this->view($user);
    }

    /**
     * @Security(name="api_key"),
     * @SWG\Patch(
     *     tags={"User"},
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
     * @Rest\Patch("/api/patch/users/{id}")
     */
    public function patchApiUser(User $user, Request $request, ValidatorInterface $validator)
    {
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $email = $request->get('email');
        $birthday = $request->get('birthday');
        $apiKey = $request->get('api_key');

        if ($firstname !== null) {
            $user->setFirstname($firstname);
        }
        if ($lastname !== null) {
            $user->setLastname($lastname);
        }
        if ($email !== null) {
            $user->setEmail($email);
        }
        if ($birthday !== null) {
            $user->setBirthday($birthday);
        }
        if ($apiKey !== null) {
            $user->setApiKey($apiKey);
        }

        $validationErrors = $validator->validate($user);
        if ($validationErrors->count() > 0) {
            /** @var ConstraintViolation $constraintViolation */
            foreach ($validationErrors as $constraintViolation) {
                // Returns the violation message. (Ex. This value should not be blank.)
                $message = $constraintViolation->getMessage();
                // Returns the property path from the root element to the violation. (Ex. lastname)
                $propertyPath = $constraintViolation->getPropertyPath();
                $errors[] = ['message' => $message, 'propertyPath' => $propertyPath];
            }
        }
        if (!empty($errors)) {
            // Throw a 400 Bad Request with all errors messages (Not readable, you can do better)
            throw new BadRequestHttpException(\json_encode($errors));
        }
        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user);
    }

    /**
     * @Security(name="api_key"),
     * @SWG\Delete(
     *     tags={"User"},
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
     * @Rest\Delete("/api/delete/users/{id}")
     */
    public function deleteApiUser(User $user, Request $request)
    {
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $email = $request->get('email');
        $birthday = $request->get('birthday');
        $apiKey = $request->get('api_key');

        if ($firstname !== null) {
            $user->setFirstname($firstname);
        }
        if ($lastname !== null) {
            $user->setLastname($lastname);
        }
        if ($email !== null) {
            $user->setEmail($email);
        }
        if ($birthday !== null) {
            $user->setBirthday($birthday);
        }
        if ($apiKey !== null) {
            $user->setApiKey($apiKey);
        }

        $this->em->remove($user);
        $this->em->flush();
        return $this->view($user);
    }


}
