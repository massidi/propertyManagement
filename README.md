# SYSINFO-IMMOBILIER
 
Documentation 






# CADRAGE DE L'APPLICATION


**INTRODUCTION**  : 


SYSINFO-IMMOBILIER est une application web de gestion de réservation immobilière en République Démocratique du Congo L’application est composée de plusieurs utilisateurs ayant des rôles différents (ex : ajouter des bien immobiliers, ajouter des images des bien immobiliers, ajouter une description du bien immobilier et les prix ). L'application Sysinfo-immobilier est basée sur un système de hiérarchie des rôles utilisateur dans lequel l'utilisateur ayant le rôle le plus élevé a le pouvoir de créer, supprimer, modifier un bien immobilier et créer une nouvelle réservation, contrairement aux autres utilisateurs qui ont simplement le droit soit de créer, modifier et supprimer une réservation.


La réservation s'effectue au moyen d'un formulaire à remplir, en commençant par choisir le produit que le client souhaite réserver. Ensuite, renseigner les informations du client (nom, prénom, pays, numéro de carte d'identité, type de carte d'identité adresse, email, numéro de téléphone etc.), puis procéder aux paiements, pour enfin confirmer la réservation. 
Pour être signalé du statut de la réservation, le client recevra un SMS ou un email concernant celui-ci.


Dans la création d'un espace entreprise, chaque produit est lié à une entreprise et, est basée sur une relation un-à-plusieurs où une entreprise peut avoir un ou plusieurs produits et les informations de l'entreprise telles que (nom de l'entreprise, numéro niss, numéro de registre, nom de responsable, date de création, logo, adresse, email, site internet, numéro de pays, région/ville, numéro de téléphone).


L’application possède aussi une mise en place d'un système de calendrier en page d'accueil pour afficher le détail des réservations concernant une facture payée ou impayée ainsi que la date d'entrée et de chaque sortie d'un client.




Conçu et développé  selon  les  règles  générales  régissant  le fonctionnement de gestion immobilière, SYSINFO-IMMOBILIER est un logiciel développé sous le framework “ PHP Symfony “ et fonctionne sous un environnement web avec une base de données par “ Mysql “ ou “ Postgres “. 
SYSINFO-IMMOBILIER est un logiciel répondant ce qui veut dire qu’il s'adapte à tous les appareils sur lequel il est utilisé. 




**Démarrage de SYSINFO-IMMOBILIER**


Comme pour la plupart des logiciels fonctionnant sous environnement web, pour démarrer SYSINFO-IMMOBILIER,  il faut vous authentifier ( identifié ) avec un email et mot de passe.


La sécurité logicielle consiste également à protéger le logiciel par le mot de passe qui sécurise les données.

Après  l’authentification ( identification ), l'étape suivante est la création d’une société ( ou d’un compte société )




 **Configuration de l’application**


 
Après avoir été authentifié ( identifié ), la prochaine étape est la configuration des paramétrage dans l’application.
Le paramétrage ou la configuration du logiciel constitue à l’ensemble des caractéristiques techniques qui ne dépendent pas du fabricant mais découlent des choix de l'acheteur et de l'utilisateur. 










**Etapes de configuration** : 


La création d'entreprise(compte société) : Paramétrage qui ne se fait qu'une seule fois ultérieurement. Vous ne pouvez que modifier (il est vivement recommandé de ne pas supprimer un compte société au risque de perdre toutes les données)



Configuration des taxes : Pour définir les différentes taxes que vous appliquez lors de la réservation par le biais du bouton  “ Ajouter “ , qui est une étape très importante à retenir.




Configuration des Catégories ( Avant de passer à l'ajout des article )


Catégories (ex.Appartement,Residence,Maison,Studio,Bureau etc.)

Accessoires (ex.Salon, Cuisine, Nombre de chambre,TV, etc.)


 
**Création des produits (bien immobilier)**


Ici, les produits représentent le logement. Cette étape consiste à créer un bien à louer. 
C'est là que vous allez renseigner tous les détails de votre bien en remplissant le formulaire avec tous les détails possibles. Les images peuvent aussi être ajoutées. 
Néanmoins, il n'est pas obligatoire que le logement contient des images


**Moteur Réservation**


Un moteur des réservations dans le domaine de gestion d' immobilier est utilisé pour traiter les réservations en ligne en toute sécurité cette étape vous donne l'aperçu sur les différentes immobiliers proposés, leurs disponibilités selon les dates



Nous avons deux Bouton : recherche qui est utilisé comme filtre  il te permet de voir la disponibilité des immobiliers vides  selon les dates d’arrivées et  départs et le bouton Booking qui te permet de passer une réservation en ligne.



**Bouton Booking**


Passer une réservation en ajoutant les informations suivantes: date d’Arrivées, date Départ et commentaire, ensuite remplir les informations du Client. Une réservation peut avoir un ou  plusieurs clients.


SYSINFO-IMMOBILIER est équipé d'un système hautement sécurisé pour valider une réservation. Avant de procéder au paiement pour finaliser sa réservation le système vérifie si la période (date d'arrivée et de départ) choisie par le client est libre, le système détecte la disponibilité du bien immobilier qu'il soit occupé ou non si le bien immobilier est occupé vous recevez un message d'avertissement pour vous informer que le bien immobilier est occupé et le système vous redirigera vers la page de réservation pour entrer une autre date ou choisir  un autre bien immobilier au cas contraire serez dirigé vers la page du paiement




Après avoir valider sa réservation, l'étape suivante  est le paiement. La page paiement nous aide à finaliser et confirmer une réservation pour ce faire il faut remplir quelque condition telles que: la date du paiement,montant à payer, choisir la taxe correspondant et le mode du paiement(mode de règlement), la section prix il s'adapte par rapport au choix des taxes (il est conseillé de choisir la taxe avant de remplir la section Montant à payer ) en choisissant la taxe la section prix vous donnera le montant que le client doit payer le prix est calculer automatiquement comme suite:
prix = (total nombre de jours) + (total nombre de jour  x  (taxe÷ 100) ) 


**Facturation**


L'application SYSINFO-IMMOBILIER est dotée d'un système de facturation qui se fait d'une manière automatique une fois que le paiement du réservation est confirmée la facture peut être imprimée directement dans l'espace facturation de l'application, il peut être modifié.



 
**MÉTHODE UTILISÉES** : 


Agile : La méthodologie Agile se base sur un principe simple “ Planifier la totalité de votre projet dans les moindres détails avant de le développer est contre-productif. “ 
 
L'équipe est composée soit des individus et des interactions plutôt que des processus et des outils.
L’application, c'est-à-dire des fonctionnalités opérationnelles plutôt que de la documentation exhaustive 
La collaboration avec le client plutôt que la contractualisation des relations 
L’acceptation du changement qui répond aux besoins du client . 


Pourquoi la méthode agile :  
Pour nous permettre de développer des fonctionnalités tout en s’adaptant à l’avancement du projet et en respectant les délais accordés pour chaque fonctionnalité. 
