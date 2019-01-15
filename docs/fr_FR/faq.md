# FAQ

### Est-ce que ce plugin s'appuie sur des API tiers ?

> Le plugin utilise le service ADB (Android Debug Bridge) pour récupérer les informations et envoyer les commandes de votre Android.
Le plugin installe le paquet debian 'adb-tools'

### Je souhaite contribuer à l'amélioration de ce plugin, est-ce possible ?

> Bien sûr, le code est sur GitHub : vous pouvez soumettre des pull requests.

### Je ne vois pas mes applications dans le panneau droit ?

> La liste n'est pas générée dynamiquement en fonction les applications installées sur votre Android. Le nombre est limité à 6. Se reporter a la doc pour rendre visible ou non une application.

### Lorsque je clique sur une application, rien ne ce passe pourquoi?

> Entre les appareils équipés d'android TV, smartphone, box opérateurs, le nom des applications ne sont pas les memes, il faut donc modifier la commande ADB.
> 1) Pour cela lancer la commande shell ```sudo adb shell "pm list packages"|cut -f 2 -d ":"```
> 2) Repérer le nom de l'application, par exemple "com.google.android.youtube.tv"
> 3) Remplacer le nom de l'appli dans le champs commande de l'application (dans onglet "liste des applications" dans la configuration de l'équipement)

![Screenshot8](../images/Screenshot8.png)

### Je ne trouve pas l'application dans la liste des commandes disponibles, comment faire ?

> Il faut simplement créer une issue sur github : [https://github.com/NextDom/plugin-AndroidRemoteControl/issues](https://github.com/Jeedom-Plugins-Extra/plugin-AndroidRemoteControl/issues)

### Je ne vois pas l'option "débogage par reseau", que faire ?

> Sur certains appareils Android, cette option est désactivée et le port 5555 servant a ADB n'est pas ouvert par défaut, pour remedier a cela il faut executer les commandes suivantes
> - Activer le debogage USB et connecter l'appareil a votre ordinateur (ou Jeedom) en USB.
> - Si vous utiliser un  ordinateur il faudra telecharger l'application (minimal adb and fast boot).
> - Assurez vous que l'appareil est bien reconnu par l'ordinateur avec la commande "adb devices" (Votre appareil devrait etre listé)
> - Lancer la commande "adb tcpip 5555" (cette commande ouvre le port 5555)
> - Vous pouvez maintenant deconnecter le cable USB et profiter de votre plugin.

### Le plugin est gratuit, le restera t il ?

> Bien sûr, nous ne jurons que par du libre, du gratuit et de l'Open Source.
