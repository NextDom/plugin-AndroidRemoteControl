# FAQ

### Est-ce que ce plugin s'appuie sur des API tiers ?

> Le plugin utilise le service ADB (Android Debug Bridge) pour récupérer les informations et envoyer les commandes de votre Android.
Le plugin installe le paquet debian 'adb-tools'

### Je souhaite contribuer à l'amélioration de ce plugin, est-ce possible ?

> Bien sûr, le code est sur GitHub : vous pouvez soumettre des pull requests.

### Je ne vois pas mes applications dans le bandeau du bas ?

> La liste n'est pas générée dynamiquement en fonction les applications installées sur votre Android. Le nombre est limité à 6. Se reporter a la doc pour rendre visible ou non une application.

### Je ne trouve pas l'application dans la liste des commandes disponibles, comment faire ?

> Il faut simplement créer une issue sur github : [https://github.com/NextDom/plugin-AndroidRemoteControl/issues](https://github.com/NextDom/plugin-AndroidRemoteControl/issues)

### Je ne vois pas l'option "débogage par reseau", que faire ?

> Sur certains appareils Android, cette option est désactivée et le port 5555 servant a ADB n'est pas ouvert par défaut, pour remedier a cela il faut executer les commandes suivantes
> - Activer le debogage USB et connecter l'appareil a votre ordinateur (ou Jeedom) en USB.
> - Si vous utiliser un  ordinateur il faudra telecharger l'application (minimal adb and fast boot).
> - Assurez vous que l'appareil est bien reconnu par l'ordinateur avec la commande "adb devices" (Votre appareil devrait etre listé)
> - Lancer la commande "adb tcpip 5555" (cette commande ouvre le port 5555)
> - Vous pouvez maintenant deconnecter le cable USB et profiter de votre plugin.

### Le plugin est gratuit, le restera t il ?

> Bien sûr, nous ne jurons que par du libre, du gratuit et de l'Open Source.