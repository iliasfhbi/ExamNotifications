# Exam Notifications Plugin

ILIAS-Plugin für das Senden von Benachrichtigungen an Studierende während einer Klausur

## Nutzen

Während einer Prüfung können die Prüfenden Studierenden Nachrichten zukommen lassen. Die Funktion muss dabei nicht über Drittprogramme wie Zoom bereitgestellt werden, sie ist in ILIAS integriert.

## Kompatibilität

Das Plugin wurde mit Version 5.4 getestet.

**Empfehlung:** im Testobjekt sollte im Reiter _Einstellungen_ unter _Durchführung: Steuerung Testdurchlauf_ die _Prüfungsansicht_ aktiviert werden, damit die Darstellung in Kombination mit der ILIAS-Werkzeugleiste am oberen Rand harmoniert.

## Funktionsweise

### Für den Prüfenden
Die Nachricht wird im Testobjekt auf dem Reiter _Dashboard_ eingestellt. Es stehen zwei Nachrichtentypen zur Auswahl: _Information_ und _Warnung_

### Für die Studierenden
Alle 30 Sekunden wird die Nachricht abgefragt. Falls eine Nachricht eingestellt wurde, wird diese angezeigt.

Die Nachricht wird als andere Elemente überlagerndes Banner angezeigt, damit die Studierenden auf die Nachricht aufmerksam werden. Gleichzeitig ist es weniger aufdringlich als ein modales Dialogfenster.
Die Nachricht kann mit einem Klick geschlossen werden.
