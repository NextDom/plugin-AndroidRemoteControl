# AndroidRemoteControl
Plugin AndroidRemoteControl pour Jeedom 

J'ai repris le travail de @ pour réaliser un plugin pour piloter les android TV ( Shield, freebox mini 4k, etc..)

Fonctions disponibles:

Infos:
powerstate
App encours
volume
liste appli
ifconfig

Actions:
connect, disconnect
home
power on, power off
wakeup
mute, unmute
volume+, volume-, volume xx
up, down, left, right
back
click, enter
brightness+, brightness-
start, play, pause, stop
previous, next
okgoogle
cherche, texte
youtube, francetv, plex, spotify, vlc, tf1, google, facebook, molotov, netflix

Scénarios possible (ceux que j'ai chez moi):
Allumer la box -> lancer molotov -> play avec commande vocale google home/ifttt (ex: "ok google, met la télé en route").
Commander l'allumage de l'ampli (Yamaha dans mon cas) lorsque la box est allumé (car parfois le HDMI CEC).
Si netflix lancé -> lumière salon a 50%

Installation:
sur jeedom il faut installer:
Code : Tout sélectionner

sudo apt-get install android-tools-adb
Avant la 1ere commande il faut connecter jeedom a votre box, pour cela dans votre équipements (script) il faut créer une commande connect avec pour action:
Code : Tout sélectionner

/var/www/html/plugins/script/core/ressources/ADB.sh action connect 1192.168.x.x
Vous pouvez ensuite utiliser les autres commandes comme bon vous semble :)

Conseils:
Il faut créer un scénario au démarrage de jeedom qui lance la commande connect, vous n'aurez plus a vous soucier de cela apres.
Code : Tout sélectionner

- Nom du scénario : launch_adb
- Objet parent : System
- Mode du scénario : provoke
    - Evènement : #start#
    - Evènement : #[Salon][Shield][Encours]#
    
    SI #[Salon][Shield][Encours]# == "error: device not found"  
    ALORS
     #[Salon][Shield][Connect]# - Options : {"background":"0","enable":"1"}
    SINON