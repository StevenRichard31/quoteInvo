<?php

namespace Components\Utils;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * Class AfterLoginRedirection
 *
 * @package AppBundle\AppListener
 */
class AfterLoginRedirection implements AuthenticationSuccessHandlerInterface
{
    private $router;

    /**
     * AfterLoginRedirection constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Request        $request
     *
     * @param TokenInterface $token
     *
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $roles = $token->getRoles();

        $rolesTab = array_map(function ($role) {
            return $role->getRole();
        }, $roles);

        if (in_array('ROLE_ADMIN', $rolesTab, true)) {
            // c'est un administrateur : on le rediriger vers l'espace "homepage"
            $redirection = new RedirectResponse($this->router->generate('homepage'));
        }
        elseif (in_array('ROLE_USER', $rolesTab, true)){
            // c'est un utilisaeur authentifier : on le rediriger vers l'espace "devis"
            $redirection = new RedirectResponse($this->router->generate('quote.index'));
        }
        else {
            // c'est un utilisaeur lambda : on le rediriger vers "login"
            $redirection = new RedirectResponse($this->router->generate('fos_user_login'));
        }

        return $redirection;
    }
}