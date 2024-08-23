# Plugin [BroadCaster](https://github.com/Synopsie/BroadCaster) 📢

## Fonctionnalités ⚒ 

- **Commande** : Utilisez `/bc` pour faire une annonce à tous les joueurs.
- **Configurable** : Diverses options de personnalisation.
- **Permissions** : Contrôlez l'accès aux commandes.

## Configuration 🔨

```yaml
# BroadCaster plugin config.

command:
  name: 'broadcast'
  description: 'Broadcast a message to all players.'
  usage: '/broadcast <type> <message>'
  aliases: ['bc']
  permission:
    name: 'broadcast.use'
    default: 'operator'

unregister.say.command: false

use.sound: true
sound:
  name: 'note.bell'
  volume: 100
  pitch: 1

broadcast.toast.title: "§cBroadCast !"

# Interval de message destiné aux messages exéptionnels.
broadcastMessage.Interval: 300
broadcast:
  format: '§7[§6Broadcast§7] §f%message%' #Possibilité de mettre %player% pour afficher le nom du joueur.

broadcasts:
  0:
    message: 'Welcome to the server!'
    interval: 300
    type: chat #chat, popup, tip, actionbar, toast
```

---

![BroadCaster](broadcaster.png)