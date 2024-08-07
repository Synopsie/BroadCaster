# Plugin [BroadCaster](https://github.com/Synopsie/BroadCaster) 📢

Ce plugin fera en sorte d'écrire des messages automatiques au moment souhaitez et entièremenet configurable.
## Fonctionnalités :tools:

- **Commande** : Utilisez `/bc` pour faire une annonce à tous les joueurs.
- **Configurable** : Diverses options de personnalisation.
- **Permissions** : Contrôlez l'accès aux commandes.

## Configuration 🔨

```yaml
# BroadCaster plugin config.

command:
  name: 'broadcast'
  description: 'Broadcast a message to all players.'
  usage: '/broadcast <message>'
  aliases: ['bc']
  permission:
    name: 'broadcast.use'
    default: 'operator'

unregister.say.command: false

# Interval de message destiné aux messages exéptionnels.
broadcastMessage.Interval: 300
broadcast:
  format: '§7[§6Broadcast§7] §f%message%' #Possibilité de mettre %player% pour afficher le nom du joueur.
  type: chat # popup actionbar, tip, chat

message.type: chat # popup, actionbar, tip, chat
broadcasts:
  0:
    message: 'Welcome to the server!'
    interval: 300
```

## Licence :scroll:

Sous licence MIT. Voir [LICENSE](https://github.com/Synopsie/BroadCaster/blob/v1.0.0/LICENSE) pour plus de détails.
---

![BroadCaster](broadcaster.png)