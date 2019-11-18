![icon](../images/AndroidRemoteControl_icon.png)

# Description

Plugin permettant de piloter les terminaux android (TV, Shield, Freebox mini 4k, Smartphone, etc..)

### Fonctions disponibles
Infos :
* nom de l'appareil
* état (allumé/éteint) (buggé pour l'instant)
* application en cours
* résolution
* version Android
* espace disque disponible
* status de lecture (play, pause, arrêt)
* nom du titre en cours de lecture

Actions :
* accueil, retour
* allumage, extinction
* volume+, volume-, slider volume
* haut, bas, gauche, droite
* clic, entrer
* démarrage, lecture, pause, arrêt
* précédent, suivant
* lancement des applications : Youtube, FranceTV, Plex, Spotify, VLC, TF1, Google, Facebook, Molotov, Netflix, etc.

Scénarios possibles :
* Allumer la box -> lancer Molotov -> lecture avec commande vocale Google Home/ifttt (ex: "OK Google, mets la télé en route").
* Commander l'allumage de l'ampli (Yamaha dans mon cas) lorsque la box est allumée (car parfois le HDMI CEC).
* Si Netflix lancé -> lumière salon à 50%

### Equipements testés
Actuellement, le plugin a été vérifié sur les matériels suivants:
* Nvidia Shield (pas de configurations supplémentaires à effectuer).
* Oneplus 5t (pas de configurations supplémentaires à effectuer).
* Freebox mini 4k (pas de configurations supplémentaires à effectuer).
* Xiaomi Mibox TV (Le port 5555 servant à ADB n'est pas ouvert par défaut), il faut connecter la box en USB et lancer les commandes suivantes:
    - adb connect
    - adb tcpip 5555
    - adb connect 192.168.x.x:5555
    - débrancher le cable
* Samsung Galaxy (Le port 5555 servant à ADB n'est pas ouvert par défaut), il faut connecter la téléphone en USB et lancer les commandes suivantes:
    - adb connect
    - adb tcpip 5555
    - adb connect 192.168.x.x:5555
    - débrancher le cable
*  Lenovo Yoga 1 (pas de configurations supplémentaires à effectuer).


![Screenshot5](../images/Screenshot3.png)

Vous pouvez également changer la couleur du bandeau du bas ou le rendre transparent.

![Screenshot6](../images/Screenshot4.png)

# Market

Retrouvez le sur le [Market NextDom](https://nextdom.github.io/plugin-AlternativeMarketForJeedom/fr_FR/)

# Prévisualisation

![Screenshot1](../images/Screenshot1.png)

![Screenshot2](../images/Screenshot2.png)

![Screenshot3](../images/Screenshot3.png)

![Screenshot4](../images/Screenshot4.png)

![Screenshot7](../images/Screenshot7.png)

Lien vers le [Forum](https://www.nextdom.org/forum/plugin-android-remote-control)
