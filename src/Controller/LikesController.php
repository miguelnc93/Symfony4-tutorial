<?php
/**
 * Created by PhpStorm.
 * User: UserName
 * Date: 9/6/2018
 * Time: 11:06 AM
 */

namespace App\Controller;


use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LikesController
 * @Route("/likes")
 */
class LikesController extends Controller
{
    /**
     * @param MicroPost $microPost
     * @return JsonResponse
     * @Route("/like/{id}", name="likes_like")
     */
    public function like(MicroPost $microPost)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if(!$currentUser instanceof User)
        {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $microPost->like($currentUser);

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'count' => $microPost->getLikedBy()->count()
        ]);
    }

    /**
     * @param MicroPost $microPost
     * @return JsonResponse
     * @Route("/unlike/{id}", name="likes_unlike")
     */
    public function unlike(MicroPost $microPost)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if(!$currentUser instanceof User)
        {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $microPost->getLikedBy()->removeElement($currentUser);

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'count' => $microPost->getLikedBy()->count()
        ]);
    }

}