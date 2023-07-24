# Pilvilinna API

## Johdanto

Pilvilinna API on PHP-pohjainen palvelurajapinta Pilvilinna-palvelulle. Sen avulla voidaan hallita tiedostoja ja kansioita Pilvilinna-palvelussa.

Pilvilinna on opetustarkoitukseen suunnattu palvelu, joka demonstroi erilaisia web-sovellusten haavoittuvuuksia. Huomioithan, että Pilvilinna sisältää tietoturva-aukkoja tarkoituksellisesti, joten sitä ei tule käyttää tuotantoympäristössä.

## Asennus

Pilvilinna API on suunniteltu toimimaan Apache-webpalvelimella. Voit kloonata GitHub-repositorion ja asentaa Pilvilinna API:n seuraavasti:

```bash
git clone https://github.com/yourusername/pilvilinna-api.git
cd pilvilinna-api
composer install
```

## Käyttöönotto
Muokkaa tiedostoa config/db.inc määrittääksesi tietokantayhteyden asetukset.

## Käyttöliittymä
Pilvilinna API tarjoaa seuraavat päätepisteet:

- /auth.php: Autentikaatio
- /status.php: API:n tila
- /files.php: Tiedostojen hallinta
- /folder.php: Kansioiden hallinta
- /register.php: Palveluun rekisteröityminen

## Lisenssi
Pilvilinna API on lisensoitu MIT-lisenssillä.
