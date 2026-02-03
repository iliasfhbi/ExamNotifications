# Plugin Tests

----

**Inhalt**

[TOC]

---

## 1. Voraussetzungen

- ILIAS `9` Installation
- PHP `8.1`
- Das Plugin wurde korrekt installiert, Installationsanleitung siehe **[`../README.md`](../README.md)**

_[Inhaltsverzeichnis](#plugin-tests)_

------

## 2. Bedienung

### 2.1 Installation / Update von PHPStan

Ausgehend vom Plugin Verzeichnis:

```bash
cd test
composer update
```

### 2.2 Ausführen von PHPStan

Ausgehend vom Plugin Verzeichnis:

```bash
cd test
php8.1 vendor/bin/phpstan
# Oder um ein Resultat im Junit-xml format zu erhalten:
vendor/bin/phpstan --error-format=junit > ~/phpstan-junit-report.xml
```

_[Inhaltsverzeichnis](#plugin-tests)_
