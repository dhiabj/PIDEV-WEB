<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\LoginAttempt;
use App\Repository\LoginAttemptRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;


class AppAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $loginAttemptRepository;
    private $userRepository;
    private $userProvider;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder,LoginAttemptRepository $loginAttemptRepository, UserRepository $userRepository, UserProviderInterface $userProvider)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->loginAttemptRepository = $loginAttemptRepository;
        $this->userRepository = $userRepository;
        $this->userProvider = $userProvider;

    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );
        if(!$this->passwordEncoder->isPasswordValid($this->getUser($credentials,$this->userProvider), $credentials['password'])){
            $newLoginAttempt = new LoginAttempt($request->getClientIp(), $credentials['email']);
            $this->entityManager->persist($newLoginAttempt);
            $this->entityManager->flush();
        }

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        if (!$user) {
            throw new UsernameNotFoundException('Email could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        /*$userRepository =$this->getDoctrine()->getRepository(User::class);*/
        if(!$this->passwordEncoder->isPasswordValid($user, $credentials['password'])){
            if ($this->loginAttemptRepository->countRecentLoginAttempts($credentials['email']) >=3 and $this->loginAttemptRepository->countRecentLoginAttempts($credentials['email'])<5) {

                /*$user = $userRepository->find($request->query->get('id'));*/
                throw new CustomUserMessageAuthenticationException('Vous avez essayé de vous connecter avec un mot'
                    .' de passe incorrect de trop nombreuses fois. Veuillez patienter svp avant de ré-essayer.');
            }
            if ($this->loginAttemptRepository->countRecentLoginAttempts($credentials['email']) >= 5) {

                /*$user = $userRepository->find($request->query->get('id'));*/
                $user->setEtat("Banned");
                $this->userRepository->add($user,true);
                throw new CustomUserMessageAuthenticationException('Votre compte a été banni à cause des rasiooooo');
            }
        }
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }



    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
        $user = $token->getUser();
        $this->loginAttemptRepository->deleteAttempts($user->getUsername());

        if(in_array('ROLE_ADMIN',$user->getRoles(),true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin'));
        }
        if(in_array('ROLE_LIVREUR',$user->getRoles(),true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_livreur'));
        }
        if(in_array('ROLE_USER',$user->getRoles(),true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_user'));
        }
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
