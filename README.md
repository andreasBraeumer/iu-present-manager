# Geschenke-Manager

Symfony-Anwendung zur Verwaltung von Personen, Anlässen, Geschenkideen und Geschenken.

## Regelmäßige Benachrichtigungen einrichten

Die Anwendung erzeugt Benachrichtigungen (Weihnachts-Status, Geburtstage im nächsten Monat) nicht von
selbst – dafür muss der folgende Befehl einmal täglich aufgerufen werden, z. B. über einen Cronjob auf dem
Server:

```
php bin/console app:benachrichtigung:taeglich
```

Beispiel für einen täglichen Cronjob um 6:00 Uhr:

```
0 6 * * * cd /pfad/zum/projekt && php bin/console app:benachrichtigung:taeglich >> var/log/benachrichtigungen.log 2>&1
```

Der Befehl ruft intern `app:benachrichtigung:weihnachtsstatus` (nur vom 1. bis 25. Dezember) und
`app:benachrichtigung:geburtstage` (nur am 1. eines Monats) auf.
