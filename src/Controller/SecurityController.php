<?php
	
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Form\ChangePasswordFormType;
use App\Form\RemindPasswordFormType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\User;

class SecurityController extends AbstractController
{
	
	private $passwordEncoder;
	private $mailer;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
    
    /**
     * @Route("/change_password", name="app_change_password")
     */
    public function changePassword(Request $request): Response
    {
        $user = $this->getUser();
        
        if(!$user){
	        throw new AccessDeniedException('В доступе отказано!');
        }
        
        $form = $this->createForm(ChangePasswordFormType::class);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
	        $em = $this->getDoctrine()->getManager();
	        $user->setPassword($this->passwordEncoder->encodePassword($user, $form->getData()['password']));
	        $em->persist($user);
	        $em->flush();
	        return new RedirectResponse('/user');
	    }
        
        return $this->render('security/changePassword.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/remind_password", name="app_remind_password")
     */
    public function remindPassword(Request $request): Response
    {
        
        $form = $this->createForm(RemindPasswordFormType::class);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
	        $password = rand(1000000,9999999);
	        
	        $em = $this->getDoctrine()->getManager();
	        $user = $em->getRepository(User::class)->findOneByEmail($form->getData()['email']);
	        if($user){
		        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
		        $em->persist($user);
		        $em->flush();
		        
		        $message = (new Email())
		        ->from('example@example.com')
		        ->subject('Напоминание пароля')
		        ->to($user->getEmail())
		        ->html(
		            'Ваш новый пароль: <b>' . $password . '</b>'
		        );
		
		        $this->mailer->send($message);
		        
		        return new RedirectResponse($this->generateUrl('app_login'));
	        }
	    }
        
        return $this->render('security/remindPassword.html.twig', [
            'form' => $form->createView()
        ]);
    }
}