<?php

namespace App\Controller;

use App\Entity\Entreprise;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EntrepriseType;
use App\Form\EmployeType;
use App\Form\EmployeProfilType;
use App\Form\UpdateEntrepriseType;
use App\Form\UpdateEmployeType;
use App\Entity\Employe;
use App\Entity\Stage;
use App\Entity\Profil;
use App\Entity\EmployeProfil;
use App\Form\StageType;
use App\Form\ProfilType;
use App\Form\EtudiantType;
use App\Entity\Etudiant;
use App\Entity\EtudiantStage;
use App\Form\EtudiantStageType;
use App\Entity\EntrepriseStage;
use App\Entity\Utilisateur;
use App\Form\EntrepriseStageType;
use App\Form\UpdateStageType;
use App\Form\UpdateProfilType;
use App\Form\UpdateEtudiantType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UpdateUtilisateurType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\AjoutUtilisateurType;



#[Route('/accueil', name: 'app_home')]
#[IsGranted('ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN')]
class HomeController extends AbstractController
{

    /**
     * @Route("/accueil", name="app_home")
     */
    public function Home(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $listeEntreprises = $entityManager->getRepository(Entreprise::class)->findBy(array(), array('nom' => 'ASC'));
        $listeEmploye = $entityManager->getRepository(Employe::class)->findBy(array(), array('nom' => 'ASC'));
        $listeEtudiant = $entityManager->getRepository(Etudiant::class)->findBy(array(), array('nom' => 'ASC'));
        $listeProfil = $entityManager->getRepository(Profil::class)->findBy(array(), array('nom' => 'ASC'));
        $listeStage = $entityManager->getRepository(Stage::class)->findBy(array(), array('code' => 'ASC'));
        return $this->render('home/index.html.twig', ['listeEntreprises' => $listeEntreprises, 'listeEmploye' => $listeEmploye, 'listeEtudiant' => $listeEtudiant, 'listeProfil' => $listeProfil, 'listeStage' => $listeStage]);
    }
    /**
     * @Route("/recherche", name="app_search")
     */
    public function filterSearch(Request $request, EntityManagerInterface $entityManager)
    {
        // Récupère la chaîne de recherche à partir de la requête GET
        $search = $request->query->get('entreprise');

        // Récupère le dépôt (repository) de l'entité Entreprise pour exécuter des requêtes
        $entrepriseRepository = $entityManager->getRepository(Entreprise::class);
        // Crée une requête pour trouver toutes les entreprises qui ont un nom qui correspond à la chaîne de recherche
        $entreprises = $entrepriseRepository->createQueryBuilder('e')
            ->where('e.nom LIKE :search OR e.pays LIKE :search OR e.ville like :search OR e.rs like :search OR e.specialite like :search OR e.adresse like :search OR e.cp like :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();
            
        return $this->render('home/recherche.html.twig', ['search' => $search, 'entreprises' => $entreprises]);
    }


    /** 
     *@Route("detailEntreprise/{id}", name="detailEntreprise")
     */
    function detailEntreprise($id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $entreprise = $doctrine->getRepository(Entreprise::class)->find($id);
        return $this->render('home/detailEntreprise.html.twig', ['entreprise' => $entreprise, 'id' => $id]);
    }
    /** 
     *@Route("/ajoutEntreprise", name="ajoutEntreprise")
     */
    function ajoutEntreprise(Request $requestHTTP, ManagerRegistry $doctrine)
    {
        $entreprise = new Entreprise();
        $formulaireEntreprise = $this->createForm(EntrepriseType::class, $entreprise);
        $formulaireEntreprise->handleRequest($requestHTTP);
        if ($formulaireEntreprise->isSubmitted() && $formulaireEntreprise->isValid()) {
            $entrepriseInfo = $formulaireEntreprise->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($entrepriseInfo);
            $entityManager->flush();
            return $this->redirectToRoute("app_home");
        }
        return $this->render('home/entrepriseform.html.twig', ['entrepriseform' => $formulaireEntreprise->createView()]);
    }
    /**
     *@Route("SupprimeEntreprise/{id}", name="SupprimeEntreprise")
     */
    function SupprimeEntreprise($id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $entreprise = $doctrine->getRepository(Entreprise::class)->find($id);

        $stages = $entreprise->getStage();
        foreach ($stages as $stage) {
            $entityManager->remove($stage);
        }

        foreach ($entreprise->getEmployes() as $employe) {
            $entreprise->removeEmploye($employe);
            $entityManager->persist($employe);
        }

        $entityManager->remove($entreprise);
        $entityManager->flush();

        return $this->redirectToRoute("app_home");
    }
    /** 
     *@Route("ModifEntreprise/{id}", name="ModifEntreprise")
     */
    function ModifEntreprise(Request $requestHTTP, $id, ManagerRegistry $doctrine)
    {
        $entreprise = $doctrine->getRepository(Entreprise::class)->find($id);
        $formulaireEntreprise = $this->createForm(UpdateEntrepriseType::class, $entreprise);
        $formulaireEntreprise->handleRequest($requestHTTP);
        if ($formulaireEntreprise->isSubmitted() && $formulaireEntreprise->isValid()) {
            $entrepriseInfos = $formulaireEntreprise->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($entrepriseInfos);
            $entityManager->flush();
            return $this->redirectToRoute("detailEntreprise", ['id' => $id]);
        }
        return $this->render('home/updateEntreprise.html.twig', ['updateentrepriseform' => $formulaireEntreprise->createView(), 'entreprise' => $entreprise, 'id' => $id, 'nom' => $entreprise->getNom()]);
        return $this->redirectToRoute("app_home");
    }
    /** 
     *@Route("/ajoutEmploye", name="ajoutEmploye")
     */
    public function AjouterEmploye(Request $request, ManagerRegistry $doctrine)
    {
        $employe = new Employe();
        $formEmploye = $this->createForm(EmployeType::class, $employe);
        $formEmploye->handleRequest($request);

        if ($formEmploye->isSubmitted() && $formEmploye->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($employe);
            $entityManager->flush();
            return $this->redirectToRoute("app_home");
        }

        return $this->render('home/employeform.html.twig', ['employeform' => $formEmploye->createView()]);
    }
    /** 
     *@Route("/ajoutEmployeProfil", name="ajoutEmployeProfil")
     */
    public function AjouterEmployeProfil(Request $request, ManagerRegistry $doctrine)
    {
        $employeProfil = new EmployeProfil();
        $formEmployeProfil = $this->createForm(EmployeProfilType::class, $employeProfil);
        $formEmployeProfil->handleRequest($request);

        if ($formEmployeProfil->isSubmitted() && $formEmployeProfil->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($employeProfil);
            $entityManager->flush();
            return $this->redirectToRoute("app_home");
        }

        return $this->render('home/employeprofilform.html.twig', ['employeprofilform' => $formEmployeProfil->createView()]);
    }
    /** 
     *@Route("/ajoutStage", name="ajoutStage")
     */
    public function AjouterStage(Request $request, ManagerRegistry $doctrine)
    {
        $stage = new Stage();
        $formStage = $this->createForm(StageType::class, $stage);
        $formStage->handleRequest($request);

        if ($formStage->isSubmitted() && $formStage->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($stage);
            $entityManager->flush();
            return $this->redirectToRoute("app_home");
        }

        return $this->render('home/stageform.html.twig', ['stageform' => $formStage->createView()]);
    }
    /** 
     *@Route("/ajoutEtudiant", name="ajoutEtudiant")
     */
    public function AjouterEtudiant(Request $request, ManagerRegistry $doctrine)
    {
        $etudiant = new Etudiant();
        $formEtudiant = $this->createForm(EtudiantType::class, $etudiant);
        $formEtudiant->handleRequest($request);

        if ($formEtudiant->isSubmitted() && $formEtudiant->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($etudiant);
            $entityManager->flush();
            return $this->redirectToRoute("app_home");
        }

        return $this->render('home/etudiantform.html.twig', ['etudiantform' => $formEtudiant->createView()]);
    }
    /** 
     *@Route("/ajoutProfil", name="ajoutProfil")
     */
    public function AjouterProfil(Request $request, ManagerRegistry $doctrine)
    {
        $profil = new Profil();
        $formProfil = $this->createForm(ProfilType::class, $profil);
        $formProfil->handleRequest($request);

        if ($formProfil->isSubmitted() && $formProfil->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($profil);
            $entityManager->flush();
            return $this->redirectToRoute("app_home");
        }

        return $this->render('home/profilform.html.twig', ['profilform' => $formProfil->createView()]);
    }
    /** 
     *@Route("/ajoutEtudiantStage", name="ajoutEtudiantStage")
     */
    public function AjouterEtudiantStage(Request $request, ManagerRegistry $doctrine)
    {
        $etudiantStage = new EtudiantStage();
        $formEtudiantStage = $this->createForm(EtudiantStageType::class, $etudiantStage);
        $formEtudiantStage->handleRequest($request);

        if ($formEtudiantStage->isSubmitted() && $formEtudiantStage->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($etudiantStage);
            $entityManager->flush();
            return $this->redirectToRoute("app_home");
        }

        return $this->render('home/etudiantstageform.html.twig', ['etudiantstageform' => $formEtudiantStage->createView()]);
    }
    /** 
     *@Route("/ajoutStageEntreprise", name="ajoutStageEntreprise")
     */
    public function AjouterStageEnreprise(Request $request, ManagerRegistry $doctrine)
    {
        $entreprisestage = new EntrepriseStage();
        $formEntrepriseStage = $this->createForm(EntrepriseStageType::class, $entreprisestage);
        $formEntrepriseStage->handleRequest($request);

        if ($formEntrepriseStage->isSubmitted() && $formEntrepriseStage->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($entreprisestage);
            $entityManager->flush();
            return $this->redirectToRoute("app_home");
        }

        return $this->render('home/entreprisestageform.html.twig', ['entreprisestageform' => $formEntrepriseStage->createView()]);
    }
    /** 
     *@Route("detailEmploye/{id}", name="detailEmploye")
     */
    function detailEmploye($id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $employe = $doctrine->getRepository(Employe::class)->find($id);
        return $this->render('home/detailEmploye.html.twig', ['employe' => $employe, 'id' => $id]);
        //, 'nom' => $employe->getNom(), 'prenom' => $employe->getPrenom()
    }
    /** 
     *@Route("ModifEmploye/{id}", name="ModifEmploye")
     */
    function ModifEmploye(Request $requestHTTP, $id, ManagerRegistry $doctrine)
    {
        $employe = $doctrine->getRepository(Employe::class)->find($id);
        $formulaireEmploye = $this->createForm(UpdateEmployeType::class, $employe);
        $formulaireEmploye->handleRequest($requestHTTP);
        if ($formulaireEmploye->isSubmitted() && $formulaireEmploye->isValid()) {
            $employeInfos = $formulaireEmploye->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($employeInfos);
            $entityManager->flush();
            return $this->redirectToRoute("detailEmploye", ['id' => $id]);
        }
        return $this->render('home/updateEmploye.html.twig', ['updateemployeform' => $formulaireEmploye->createView(), 'employe' => $employe, 'id' => $id, 'nom' => $employe->getNom(), 'prenom' => $employe->getPrenom()]);
        return $this->redirectToRoute("app_home");
    }
    /**
     *@Route("SupprimeEmploye/{id}", name="SupprimeEmploye")
     */
    function SupprimeEmploye($id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $employe = $doctrine->getRepository(Employe::class)->find($id);

        $profils = $employe->getLeprofil();
        foreach ($profils as $profil) {
            $entityManager->remove($profil);
        }

        $entityManager->remove($employe);
        $entityManager->flush();

        return $this->redirectToRoute("app_home");
    }
    /** 
     *@Route("detailStage/{id}", name="detailStage")
     */
    function detailStage($id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $stage = $doctrine->getRepository(Stage::class)->find($id);
        return $this->render('home/detailStage.html.twig', ['stage' => $stage, 'id' => $id, 'code' => $stage->getCode()]);
    }
    /** 
     *@Route("ModifStage/{id}", name="ModifStage")
     */
    function ModifStage(Request $requestHTTP, $id, ManagerRegistry $doctrine)
    {
        $stage = $doctrine->getRepository(Stage::class)->find($id);
        $formulaireStage = $this->createForm(UpdateStageType::class, $stage);
        $formulaireStage->handleRequest($requestHTTP);
        if ($formulaireStage->isSubmitted() && $formulaireStage->isValid()) {
            $stageInfos = $formulaireStage->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($stageInfos);
            $entityManager->flush();
            return $this->redirectToRoute("detailStage", ['id' => $id]);
        }
        return $this->render('home/updateStageform.html.twig', ['updatestageform' => $formulaireStage->createView(), 'stage' => $stage, 'id' => $id]);
        return $this->redirectToRoute("app_home");
    }
    /**
     *@Route("SupprimeStage/{id}", name="SupprimeStage")
     */
    function SupprimeStage($id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $stage = $doctrine->getRepository(Stage::class)->find($id);

        $entreprises = $stage->getEntreprise();
        foreach ($entreprises as $entreprise) {
            $entityManager->remove($entreprise);
        }

        $etudiants = $stage->getEtudiant();
        foreach ($etudiants as $etudiant) {
            $entityManager->remove($etudiant);
        }

        $entityManager->remove($stage);
        $entityManager->flush();

        return $this->redirectToRoute("app_home");
    }
    /** 
     *@Route("detailEtudiant/{id}", name="detailEtudiant")
     */
    function detailEtudiant($id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $etudiant = $doctrine->getRepository(Etudiant::class)->find($id);
        return $this->render('home/detailEtudiant.html.twig', ['etudiant' => $etudiant, 'id' => $id, 'nom' => $etudiant->getNom(), 'prenom' => $etudiant->getPrenom()]);
    }
    /** 
     *@Route("ModifEtudiant/{id}", name="ModifEtudiant")
     */
    function ModifEtudiant(Request $requestHTTP, $id, ManagerRegistry $doctrine)
    {
        $etudiant = $doctrine->getRepository(Etudiant::class)->find($id);
        $formulaireEtudiant = $this->createForm(UpdateEtudiantType::class, $etudiant);
        $formulaireEtudiant->handleRequest($requestHTTP);
        if ($formulaireEtudiant->isSubmitted() && $formulaireEtudiant->isValid()) {
            $etudiantInfos = $formulaireEtudiant->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($etudiantInfos);
            $entityManager->flush();
            return $this->redirectToRoute("detailEtudiant", ['id' => $id]);
        }
        return $this->render('home/updateEtudiantform.html.twig', ['updateetudiantform' => $formulaireEtudiant->createView(), 'etudiant' => $etudiant, 'id' => $id]);
        return $this->redirectToRoute("app_home");
    }
    /**
     *@Route("SupprimeEtudiant/{id}", name="SupprimeEtudiant")
     */
    function SupprimeEtudiant($id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $etudiant = $doctrine->getRepository(Etudiant::class)->find($id);

        $stages = $etudiant->getStage();
        foreach ($stages as $stage) {
            $entityManager->remove($stage);
        }

        $entityManager->remove($etudiant);
        $entityManager->flush();

        return $this->redirectToRoute("app_home");
    }
    /** 
     *@Route("detailProfil/{id}", name="detailProfil")
     */
    function detailProfil($id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $profil = $doctrine->getRepository(Profil::class)->find($id);
        return $this->render('home/detailProfil.html.twig', ['profil' => $profil, 'id' => $id, 'nom' => $profil->getNom()]);
    }
    /** 
     *@Route("ModifProfil/{id}", name="ModifProfil")
     */
    function ModifProfil(Request $requestHTTP, $id, ManagerRegistry $doctrine)
    {
        $profil = $doctrine->getRepository(Profil::class)->find($id);
        $formulaireProfil = $this->createForm(UpdateProfilType::class, $profil);
        $formulaireProfil->handleRequest($requestHTTP);
        if ($formulaireProfil->isSubmitted() && $formulaireProfil->isValid()) {
            $profilInfos = $formulaireProfil->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($profilInfos);
            $entityManager->flush();
            return $this->redirectToRoute("detailProfil", ['id' => $id]);
        }
        return $this->render('home/updateProfilform.html.twig', ['updateprofilform' => $formulaireProfil->createView(), 'etudiant' => $profil, 'id' => $id]);
        return $this->redirectToRoute("app_home");
    }
    /**
     *@Route("SupprimeProfil/{id}", name="SupprimeProfil")
     */
    function SupprimeProfil($id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $profil = $doctrine->getRepository(Profil::class)->find($id);

        $employes = $profil->getEmploye();
        foreach ($employes as $employe) {
            $entityManager->remove($employe);
        }

        $entityManager->remove($profil);
        $entityManager->flush();

        return $this->redirectToRoute("app_home");
    }
    /**
     * @Route("/listeUtilisateur", name="listeUtilisateur")
     */
    public function listeUtilisateur(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $listeUtilisateurs = $entityManager->getRepository(Utilisateur::class)->findBy(array(), array('login' => 'ASC'));
        return $this->render('home/listeUtilisateurs.html.twig', ['listeUtilisateurs' => $listeUtilisateurs]);
    }

     /** 
     *@Route("detailUtilisateur/{id}", name="detailUtilisateur")
     */
    function detailUtilisateur($id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $utilisateur = $doctrine->getRepository(Utilisateur::class)->find($id);
        return $this->render('home/detailUtilisateur.html.twig', ['utilisateur' => $utilisateur->getLogin(), 'id' => $id, 'role' => $utilisateur->getRoles()]);
    }

    /**
     *@Route("supprimerUtilisateur/{id}", name="supprimerUtilisateur")
     */
    function supprimerUtilisateur($id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $utilisateur = $doctrine->getRepository(Utilisateur::class)->find($id);

        $entityManager->remove($utilisateur);
        $entityManager->flush();

        return $this->redirectToRoute("listeUtilisateur");
    }

    /** 
     *@Route("modifUtilisateur/{id}", name="modifUtilisateur")
     */
    function modifierUtilisateur(UserPasswordHasherInterface $utilisateurPasswordHasher, $id, ManagerRegistry $doctrine, Request $requestHTTP, EntityManagerInterface $entityManager)
    {
        $utilisateur = $doctrine->getRepository(Utilisateur::class)->find($id);
        $formulaireUtilisateur = $this->createForm(UpdateUtilisateurType::class, $utilisateur);
        $formulaireUtilisateur->handleRequest($requestHTTP);

        if ($formulaireUtilisateur->isSubmitted() && $formulaireUtilisateur->isValid()) {

            $utilisateur->setPassword(
                $utilisateurPasswordHasher->hashPassword(
                        $utilisateur,
                        $formulaireUtilisateur->get('Password')->getData()
                    )
                );
            $utilisateurInfos = $formulaireUtilisateur->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($utilisateurInfos);
            $entityManager->flush();
            return $this->redirectToRoute("detailUtilisateur", ['id' => $id]);
        }
        return $this->render('home/updateUtilisateurform.html.twig', ['updateutilisateurform' => $formulaireUtilisateur->createView(), 'utilisateur' => $utilisateur, 'id' => $id]);
        //return $this->redirectToRoute("app_home");
    }

    /**
     * @Route("/AjoutUtilisateur", name="AjoutUtilisateur")
     */
    function AjouterUtilisateur(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur();
        $formulaireUtilisateur = $this->createForm(AjoutUtilisateurType::class, $user);
        $formulaireUtilisateur->handleRequest($request);

        if ($formulaireUtilisateur->isSubmitted() && $formulaireUtilisateur->isValid()) {
            // encode the password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $formulaireUtilisateur->get('Password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            
            return $this->redirectToRoute("listeUtilisateur");
        }
        return $this->render('home/ajoutUtilisateur.html.twig', ['ajoutUtilisateurForm' => $formulaireUtilisateur->createView()]);
    }
}