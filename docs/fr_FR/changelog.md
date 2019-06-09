# Idées pour les versions suivantes :

- Générer dynamiquement la liste des applications disponibles en fonction des applications installées sur l'équipement.

# Prochaine version

Ajout des fonctionnalités suivantes:
    - Prise en charge de l'envoi de SMS sur les appareils compatibles
    - Envoi d'une notification (Toast)
    - prise en charge du control par SSH

# Changelog

### Version 0.9.9:  (16/01/2019 - Version beta)
* Ajout du template mobile 
* Correction du lancement de Molotov  
* fix bugs

### Version 0.9.8:  (25/05/2018)

* Correction de la fonction connect en fin d'Assistant  
* Ajout de la prise en charge du type de connection (USB, TCPIP)
* Amélioration de la reconnexion ADB pour les multi-devices

### Version 0.9.7:  (25/05/2018)

* Ajout du statut de charge de la batterie (En charge, en décharge, pleine)
* Le statut de lecture est maintenant de type string (lecture, pause, arret)
* Prise en charge des applications suivantes (freeboxtv, tinycam)
* Amélioration de la gestion de la reconnexion ADB (suppression du fichier reset.sh)
* Ajout d'un onglet spécifique "liste des applications" dans la configuration équipement pour la gestion des Applications
![Screenshot8](../images/Screenshot8.png)
* Il est maintenant possible d'ajouter des applications directement à partir de l'onglet "liste des applications"
* Il est maintenant possible de modifier la commande ADB dans la configuration équipement

### Version 0.9.6:  (09/05/2018)

* Modification de l'icône play/stop en fonction de l'état de lecture.
* Ajout d'un effet blur lors du survol de l'affiche (pour signifier que le bouton est cliquable)
* Ajout d'un icône reboot (en haut a gauche)
* Modification des keyevent pour plus de compatibilité

### Version 0.9.5:  (08/05/2018)

* Ajout de la fonction connect en fin d'assistant (afin d'obtenir le message d'autorisation d'adb)

### Version 0.9.4:  (06/05/2018)

* Mise à jour dynamique du template eqlogic.html lors de l'ajout d'une appli dans le json (plus besoin de modifier le dashboard.html)
* Ajout d'une pré-version de template pour la version mobile
* Ajout du status de lecture dans le panneau latéral gauche (pour l'instant à titre indicatif, 2 = pause 3 = lecture)
    - Je me servirai de ce status pour modifier l'icône de la commande play/stop
* débogage:
    - Affichage de l'appli en cours dans la commande "encours" (retournait vide avant)
    - Résolution des problèmes de masquage d'icône
* Ajout des applis suivantes:
    - Deezer
    - STB EMU PRO

### Version 0.9.3:  (03/05/2018)

* Changement de design
* Ajout d'un slider pour le réglage du volume
* Ajout du titre de la lecture en cours
* Ajout d'un panneau latéral droit pour les applications

### Version 0.9.2:  (28/04/2018)

* Correction bug sur coloration de l'icon power lorsque l'appareil est allumé
* Refonte du core pour la gestion des commandes et applications
    - Les commandes sont gérées par le fichier commandes.json
    - Les applications sont gérées par le fichier appli.json
Cela permet d'ajouter des commandes et applications sans modifier le core du plugin.
* Ajout des applis suivantes:
    - Freebox TV
    - MyCanal

### Version 0.9.1:  (26/04/2018)

* Changement de design
* Ajout d'un slider pour le réglage du volume
* Ajout du titre de la lecture en cours
* Ajout d'un paneau latéral droit pour les applications


### Version 0.9.2:  (28/04/2018)

* Correction bug sur coloration de l'icon power lorsque l'appareil est allumé
* Refonte du core pour la gestion des commandes et applications
    - Les commandes sont gérées par le fichier commandes.json
    - Les applications sont gérées par le fichier appli.json
Cela permet d'ajouter des commandes et applications sans modifier le core du plugin.

### Version 0.9.1:  (26/04/2018)

* 1ere BÊTA

### Détail des changements

Détail complet des mises à jour sur [Historique Commit](https://github.com/NextDom/plugin-AndroidRemoteControl/commits/master)

# Bug

En cas de problèmes avec ce plugin, il est possible d'ouvrir un ticket pour demander une correction :

[https://github.com/NextDom/plugin-AndroidRemoteControl/issues](https://github.com/NextDom/plugin-AndroidRemoteControl/issues)
