<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager, MailerInterface $mailer, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(["ROLE_USER"]);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $this->sendConfirmationEmail($mailer,$verifyEmailHelper,$user);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    function sendConfirmationEmail(MailerInterface $mailer, VerifyEmailHelperInterface $verifyEmailHelper, User $user){
        $signatureComponents = $verifyEmailHelper->generateSignature(
            'app_verify_email',
            $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );


        $email = (new TemplatedEmail())
            ->from(new Address('naynay.foru@gmail.com', 'foru Bot'))
            ->to($user->getEmail())
            ->subject('ForU Confirm Account')
            ->htmlTemplate('registration/emailConfirmation.html.twig')
            ->context([
                'confirmation' => $signatureComponents->getSignedUrl(),
            ]);

        $mailer->send($email);
        $this->addFlash('success',"Veuillez vérifier votre boite mail, un email de verification a été envoyé !");
    }

    /**
     * @Route("/verify", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, UserRepository $userRepository, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $user = $userRepository->find($request->query->get('id'));

        if (!$user) {
            throw $this->createNotFoundException();
        }

        try {
            $verifyEmailHelper->validateEmailConfirmation(
                $request->getUri(),
                $user->getId(),
                $user->getEmail()
            );
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('error', "Le lien de verification parait qu'il appartient a un autre compte ou email. S'il vous plait demander un nouveau lien.");
            return $this->redirectToRoute('app_home');
        }
        //Verifier si le compte est déja vérifier ou non
        if($user->getEtat() == "Verified"){
            $this->addFlash('warning', "Votre compte est déja verifié ".$user->getPrenom().' '.$user->getNom().", Vous pouvez se connecter directement");
            return $this->redirectToRoute('app_login');
        }
        elseif($user->getEtat() == "Not Verified"){
            $user->setEtat("Verified");
            $userRepository->add($user,true);
            $this->addFlash('success', 'Votre compte a été bien verifié '.$user->getPrenom().' '.$user->getNom().', Vous pouvez se connecter maintenant');
            return $this->redirectToRoute('app_login');
        }
    }
}
