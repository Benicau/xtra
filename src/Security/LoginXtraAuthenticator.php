<?php

namespace App\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginXtraAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Get the roles associated with the authenticated user
        $roles = $token->getRoleNames();

        // Check if the user has the 'ROLE_WORKER' role
        if (in_array('ROLE_WORKER', $roles)) {
            // Redirect workers to the 'app_caisse_info' route
            return new RedirectResponse($this->urlGenerator->generate('app_caisse_info'));
        }
        // Check if the user has the 'ROLE_ADMIN' role
        elseif (in_array('ROLE_ADMIN', $roles)) {
            // Redirect administrators to the 'app_admin_page' route
            return new RedirectResponse($this->urlGenerator->generate('app_admin_page'));
        }
        // If the user doesn't have 'ROLE_WORKER' or 'ROLE_ADMIN' role, redirect to logout
        else {
            return new RedirectResponse($this->urlGenerator->generate('app_logout'));
        }

        return new RedirectResponse($this->urlGenerator->generate('app_admin_page'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
