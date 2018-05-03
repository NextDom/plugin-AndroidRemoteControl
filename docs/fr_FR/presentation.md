![icon](../images/AndroidRemoteControl_icon.png)
# Description

Plugin permettant de piloter les terminaux android (TV, Shield, freebox mini 4k, etc..)

### Fonctions disponibles
Infos :
* Nom de l'appareil
* Etat (Allumé/Eteint) (buggé pour l'instant)
* App en cours
* Résolution
* Version Android
* Espace disque disponible

Actions :
* home, back
* power on, power off
* volume+, volume-, volume x
* up, down, left, right
* click, enter
* start, play, pause, stop
* previous, next
* lancement des applis: youtube, francetv, plex, spotify, vlc, tf1, google, facebook, molotov, netflix, etc...


Scénarios possibles :
* Allumer la box -> lancer molotov -> play avec commande vocale google home/ifttt (ex: "ok google, met la télé en route").
* Commander l'allumage de l'ampli (Yamaha dans mon cas) lorsque la box est allumée (car parfois le HDMI CEC).
* Si netflix lancé -> lumière salon à 50%

### Equipements testés
Actuellement le plugin a été vérifié sur les matériels suivants:
* Nvidia Shield (pas de configurations supplémentaires a effectuer).
* Oneplus 5t (pas de configurations supplémentaires a effectuer).
* Xiaomi mibox TV (Le port 5555 servant a ADB n'est pas ouvert par défaut), il faut connecter la box en USB et lancer les commandes suivantes:
    - adb connect
    - adb tcpip 5555
    - adb conncect 192.168.x.x:5555
    - debrancher le cable

### Parametrage avancé
Vous pouvez afficher ou non la liste des applications en cochant/décochant l'option afficher sur chanque commande (voir capture ci-dessous)

![Screenshot5](../images/Screenshot3.png)

Vous pouvez également changer la couleur du bandeau du bas ou le rendre transparent.

![Screenshot6](../images/Screenshot4.png)


# Market

Retrouvez le sur le [Market](https://www.jeedom.com/market/index.php?v=d&p=market&type=plugin&&name=Plugin) Jeedom

# Prévisualisation

![Screenshot1](../images/Screenshot1.png)

![Screenshot2](../images/Screenshot2.png)

![Screenshot3](../images/Screenshot3.png)

![Screenshot4](../images/Screenshot4.png)

# Forum

Lien vers le [Forum](https://www.jeedom.com/forum/viewtopic.php?t=xxxx)

~~Remplacer `t=xxxx` par le bon numéro de forum~~
