<?php

return [
	'friend_request' => "Demandes de contact",
	'friend_request:menu' => "Demandes de contact",
	'friend_request:title' => "Demandes de contact pour %s",

	'friend_request:new' => "Nouvelle demande de contact",

	'friend_request:friend:add:pending' => "Demande de contact en attente",


	// plugins settings
	'friend_request:settings:add_river' => "Créer des entéres dans la rivière lorsqu'une demande de conteact est acceptée",

	// notifications
	'friend_request:newfriend:subject' => "%s souhaite devenir votre contact !",
	'friend_request:newfriend:body' => "%s souhaite devenir votre contact ! Mais pour cela vous devez approuver sa demande... Pour accepter ou refuser cette demande de contact, veuillez vous connecter sur le site !

Vous pouvez voir les demandes en attente sur :
%s

Assurez-vous d'être connecté sur le site web avant de cliquer sur le lien suivant, sinon vous serez redirigé sur la page de connexion.

(Vous ne pouvez pas répondre à cet e-mail.)",

	// Actions
	// Add request
	'friend_request:add:failure' => "Désolé, mais à cause d'une erreur système votre demande n'a pas pu être traitée. Veuillez essayer de nouveau.",
	'friend_request:add:successful' => "Vous avez demandé à être en contact avec %s. Votre demande doit être acceptée avant que cette personne apparaisse dans votre liste de contacts.",
	'friend_request:add:exists' => "Vous avez déjà demandé à être contact de %s.",

	// Approve request
	'friend_request:approve' => "Accepter",
	'friend_request:approve:subject' => "%s a accepté votre demande de contact",
	'friend_request:approve:message' => "Bonjour %s,

%s a acccepté votre de demande de contact.",
	'friend_request:approve:successful' => "%s est maintenant votre contact",
	'friend_request:approve:fail' => "Erreur lors de la mise en contact avec %s",

	// Decline request
	'friend_request:decline' => "Décliner",
	'friend_request:decline:subject' => "%s a refusé votre demande de contact",
	'friend_request:decline:message' => "Bonjour %s,

%s a décliné votre invitation pour devenir son contact.",
	'friend_request:decline:success' => "Demande de contact refusée",
	'friend_request:decline:fail' => "Erreur lors du refus de la demande de contact, merci de réessayer.",

	// Revoke request
	'friend_request:revoke' => "Révoquer",
	'friend_request:revoke:success' => "Demande de contact révoquée avec succès",
	'friend_request:revoke:fail' => "Erreur lors de la révocation de la demande de contact, merci de réessayer",

	// Views
	// Received
	'friend_request:received:title' => "Demandes de contact reçues",
	'friend_request:received:none' => "Pas de demande en attente de votre approbation",

	// Sent
	'friend_request:sent:title' => "Demandes de contact envoyées",
	'friend_request:sent:none' => "Pas de demande envoyée en attente d'approbation",
];

