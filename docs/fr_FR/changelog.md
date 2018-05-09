# Idées pour les versions suivantes :

- Générer dynamiquement la liste des applications disponibles en fonction des applications installées sur l'équipement.

# Prochaine version

Ajout des fonctionnalités suivantes:
    - Modification de l'icone lecture/pause en fonction du statut de lecture.
    - Affichage capture d'écrans en cliquant sur l'icone du programme encours
    - Prise en charge de l'envoi de SMS sur les appareils compatibles
    - Envoi d'une notification (Toast)

# Changelog

### Version 0.9.6:  (09/05/2018 - Version en cours sur le market)

* Modification de l'icone play/stop en fonction de l'état de lecture.
* Ajout d'un effet blur lors du survole de l'affiche (pour signaifier que le bouton est cliquable)
* Ajout d'un icone reboot (en haut a gauche)
* Modification des keyvent pour plus de compatibilité

### Version 0.9.5:  (08/05/2018)

* Ajout de la fonction connect en fin d'assistant (afin d'obtenir le message d'autorisation d'adb)

### Version 0.9.4:  (06/05/2018)

* Mise a jour dynamique du template eqlogic.html lors de l'ajout d'une appli dans le json (plus besoin de modifier le dashboard.html)
* Ajout d'une pré-version de template pour la version mobile
* Ajout du statut de lecture dans le panneau latéral gauche (pour l'instant a titre indicatif, 2 = pause 3 = lecture)
    - Je me servirais de ce status pour modifier l'icone de la commande play/stop
* débogage:
    - Affichage de l'appli encours dans la commande "encours" (retournait vide avant)
    - Résolution des problemes de masquage d'icone
* Ajout des applis suivantes:
    - deezer
    - STB EMU PRO

### Version 0.9.3:  (03/05/2018)

* Changement de design
* Ajout d'un slider pour le reglage du volume
* Ajout du titre de la lecture encours
* Ajout d'un panneau latéral droit pour les applications

### Version 0.9.2:  (28/04/2018)

* Correction bug sur coloration de l'icon power lorsque l'appareil est allumé
* Refonte du core pour la gestion des commandes et applications
    - Les commandes sont gérées par le fichier commandes.json
    - Les applications sont gérées par le fichier appli.json
Cela permet d'ajouter des commandes et applications sans modifier le core du plugin.
* Ajout des appli suivantes:
    - Freebox TV
    - MyCanal

### Version 0.9.1:  (26/04/2018)

* 1ere beta

### Détail des changements

Détail complet des mises à jour sur [Historique Commit](https://github.com/NextDom/plugin-AndroidRemoteControl/commits/master)

# Bug

En cas de problèmes avec ce plugin, il est possible d'ouvrir un ticket pour demander une correction :

[https://github.com/NextDom/plugin-AndroidRemoteControl/issues](https://github.com/NextDom/plugin-AndroidRemoteControl/issues)
