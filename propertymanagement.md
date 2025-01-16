classDiagram
direction BT
class accessoires {
   varchar(255) nom
   int id
}
class accessoires_appartement {
   int accessoires_id
   int appartement_id
}
class appartement {
   int category_id
   int commune_id
   int society_id
   varchar(255) nom
   varchar(255) price
   tinyint(1) status
   varchar(255) adresse
   varchar(120) slug
   int id
}
class booking {
   int appartement_id
   datetime check_in_at
   datetime check_out_at
   varchar(255) comment
   int id
}
class category {
   varchar(255) nom
   varchar(255) descriptions
   int id
}
class client {
   int booking_id
   varchar(255) nom
   varchar(255) prenom
   varchar(255) email
   varchar(255) telephone
   varchar(255) pays
   varchar(255) card_id
   id_type  /* (DC2Type:array) */ longtext
   int id
}
class commune {
   varchar(255) nom
   int id
}
class doctrine_migration_versions {
   datetime executed_at
   int execution_time
   varchar(191) version
}
class facturation {
   int booking_id
   int tax_id
   date payment_at
   varchar(255) mode_reglement
   datetime created_ad
   double amount
   int id
}
class image {
   int appartement_id
   varchar(255) nom
   int id
}
class messenger_messages {
   longtext body
   longtext headers
   varchar(255) queue_name
   datetime created_at
   datetime available_at
   datetime delivered_at
   bigint id
}
class society {
   varchar(255) name
   varchar(255) adresse
   varchar(255) adresse_sociale
   varchar(255) site_web
   varchar(255) numero_registre
   varchar(255) nature_societe
   varchar(255) numero_impot
   varchar(255) numero_inss
   varchar(255) code_province
   varchar(255) image
   varchar(255) phone_nbr
   varchar(255) email
   date created_at
   varchar(255) responsable_name
   int id
}
class tax {
   varchar(255) nom
   double value
   int id
}
class users {
   int society_id
   varchar(180) email
   json roles
   varchar(255) password
   varchar(255) name
   varchar(255) last_name
   int id
}

accessoires_appartement  -->  accessoires : accessoires_id:id
accessoires_appartement  -->  appartement : appartement_id:id
appartement  -->  category : category_id:id
appartement  -->  commune : commune_id:id
appartement  -->  society : society_id:id
booking  -->  appartement : appartement_id:id
client  -->  booking : booking_id:id
facturation  -->  booking : booking_id:id
facturation  -->  tax : tax_id:id
image  -->  appartement : appartement_id:id
users  -->  society : society_id:id
