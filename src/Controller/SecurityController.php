<?php
/**
 * Created by PhpStorm.
 * User: UserName
 * Date: 9/1/2018
 * Time: 11:04 AM
 */

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, TokenStorageInterface $tokenStorage)
    {
        if ($tokenStorage->getToken() && $tokenStorage->getToken()->getUser() instanceof User)
        {
            return new RedirectResponse('/micro-post');
        }


        return new Response($this->twig->render(
            'security/login.html.twig',
            [
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError()
            ]
        ));
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(){ }


    /**
     * @Route("/confirm/{token}", name="security_confirm")
     */
    public function confirm(string $token, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $user = $userRepository->findOneBy([
            'confirmationToken' => $token
        ]);

        if(null != $user)
        {
            $user->setEnabled(true);
            $user->setConfirmationToken('');

            $entityManager->flush();
        }
        return new Response($this->twig->render('security/confirmation.html.twig', [
            'user' => $user
                ]
        ));
    }
}