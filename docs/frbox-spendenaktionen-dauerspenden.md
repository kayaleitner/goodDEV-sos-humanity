# FRBox Spendenaktionen – Dauerspenden-Barometer & Spenderliste

Per-Aktion **Dauerspenden-Barometer** (Schiff-Optik) und **Spenderliste mit
Dauerspende/Einzelspende-Markierung** für die FundraisingBox-Spendenaktionen
(„Fundraising Pages") von SOS-Humanity.

> Status: funktionsfähig, aktuell als **temporäre Demo** über die Kinsta-Stage
> ausgeliefert. Für die Produktion sind noch Entscheidungen offen (siehe unten).

---

## 1. Problem & Lösungsidee

Eine Spendenaktion (`…/spenden/spendenaktion/?cfd=XXXXX`) wird komplett in **einem
cross-origin iframe** der FRBox gerendert (`secure.fundraisingbox.com`, erzeugt vom
Widget-Script `widgetJS?cfh=976em75d`). Inhalt: FRBox-Barometer + Beschreibung +
Spenderliste + Formular.

- Von WordPress aus kommt man **nicht** in dieses iframe (cross-origin).
- Anpassen geht nur über **CSS/JS, das im FRBox-Backend hinterlegt wird** (global für
  alle Aktionen) – dasselbe Prinzip wie das bestehende Formular-Styling.
- Der „Dauerspende vs. Einzelspende"-Marker steht **weder** im FRBox-DOM **noch** im
  öffentlichen JSON-Feed → er kommt nur aus der **authentifizierten FRBox-REST-API**.

**Lösung:** Wir rendern Barometer und Liste als eigene **REST-Embeds** (WordPress,
Daten aus der FRBox-API) und betten sie per FRBox-Backend-JS als `<iframe>` in die
Aktionsseite ein.

```
FRBox-Aktion (iframe, secure.fundraisingbox.com)
  └─ FRBox-Backend-JS fügt <iframe> ein →  WP-REST-Embed (Daten via FRBox-API)
```

---

## 2. Datenpfad (vollautomatisch über den `cfd`)

```
cfd aus URL (z.B. su995)
  → pages.json                    (Page finden, deren link/json_link den cfd enthält)
  → fb_fundraising_page_id        (interne ID, z.B. 72512)
  → donations.json?fb_fundraising_page_id=…
  → Split: Dauerspende, wenn fb_recurring_payment_id gesetzt; sonst Einzelspende
```

### Wichtige FRBox-Eigenheiten (verifiziert)
- ⚠️ Die FRBox-UI-„Spendenaktions-ID" ist **nicht** die API-ID. API filtert nur über
  **`fb_fundraising_page_id`** (`fb_project_id` und `*_promotion_code` funktionieren NICHT).
- ⚠️ Dauerspende-Marker = **`fb_recurring_payment_id` gesetzt**. **NICHT** `by_recurring`
  (das markiert nur automatische Folgezahlungen und ist bei Aktions-Spenden immer 0).
- Der API-Token braucht das Leserecht **„Spendenaktionen"** (Account → Nutzerverwaltung
  → API-Nutzer → Nutzerrechte). Ohne das gibt `pages.json` HTTP 400 `no_permission`.
- `recurrings.json` ignoriert den Page-Filter (gibt alles zurück) → nicht verwenden.
- Pages haben `fb_custom_fields` (für ein evtl. per-Aktion-Ziel nutzbar, s. offene Punkte).
- Öffentlicher Feed pro Aktion (ohne Token): `https://secure.fundraisingbox.com/pages_render/{cfh}/{cfd}.json`
  – enthält goal/received/donations[name,amount,date,message], **aber keinen** Dauerspende-Marker.

---

## 3. Code (WordPress / Theme)

| Datei | Zweck |
|---|---|
| `inc/frboxFundraisingPageData.php` | Datenlayer (FRBox-API). Funktionen siehe unten. |
| `inc/rest-embed-donation-barometer.php` | REST-Embed Barometer (Schiff). Quelle: `cfd` **oder** `search_id` (Legacy/OBS). |
| `inc/rest-embed-donation-list.php` | REST-Embed Spenderliste mit Dauerspende/Einzelspende + Kommentar. |
| `inc/disableRestApi.php` | REST ist global gesperrt → unsere Routen stehen in der Allowlist. |
| `Components/DonationBarometer/` | Wiederverwendete Barometer-Komponente (Schiff/Balken/Count-up). |

Token/Basis-URL liegen in den WP-Optionen `fundraisingbox_api_access_token` und
`fundraisingbox_api_base_url`. Caching via Transients (`barometer_data_cache_expiration`,
Default 3 Min); `pages.json` 1 h. Es werden nur **öffentliche** Daten zwischengespeichert.

### Datenlayer-Funktionen (`Flynt\FRBox`)
- `get_fundraising_page_by_cfd($cfd)` → `['id','goal','received','donation_count','title','status']`
- `get_action_recurring_stats($pageId)` → recurring/onetime/total counts + sums
- `get_action_recurring_by_cfd($cfd)` → `['page','stats']`
- `get_action_donor_list($pageId)` → `[ ['name','amount','date','message','recurring'], … ]` (neueste zuerst)
- `get_action_donor_list_by_cfd($cfd)` → `['page','list']`

---

## 4. REST-Endpoints

Alle öffentlich lesbar (kein Token im Frontend), nur Aggregate/öffentliche Daten.
`frame-ancestors`-CSP erlaubt Einbettung auf `sos-humanity.org`, `*.kinsta.cloud`,
`secure.fundraisingbox.com` (+ Streaming-Dienste).

### Barometer
```
/wp-json/sos/v1/barometer/embed
  ?cfd=su995            (per-Aktion-Quelle)  – ODER ?search_id=… (Legacy/OBS)
  &metric=count|sum     count = Anzahl Dauerspender, sum = Intervallsumme
  &goal=50              optional; ohne goal → dynamisches „rundes" Ziel (~70% Füllung)
  &theme=light|dark
  &transparent=1
  &barometer_text_current=Schon%20{donor_count}%20Dauerspender:innen!
  &refresh_interval=60  optional (OBS Auto-Reload)
```
Hinweis: `{donor_count}`/`{current_amount}` werden serverseitig ersetzt; die kompakte
Optik (full-width, padding 0) ist auf `cfd`-Embeds gescopet (`body.sos-embed-compact`).

### Spenderliste
```
/wp-json/sos/v1/donorlist/embed
  ?cfd=su995
  &theme=light|dark
  &transparent=1
  &limit=0              0 = alle
  &label_recurring=Dauerspende
  &label_onetime=Einzelspende
  &title=Wir%20retten%20mit:%20({count})   {count} = Anzahl Einträge
```

---

## 5. FRBox-Backend-Snippets (einzufügen im FRBox-Backend, global)

> Diese laufen **innerhalb** des FRBox-iframes. `<iframe src>` zeigt aktuell auf die
> **Kinsta-Stage** (Demo). Für Produktion: Embeds auf Prod deployen und URL auf
> `https://sos-humanity.org/...` ändern.

### CSS-Feld
```css
/* Spenderliste (+ "mehr anzeigen") ausblenden – wird durch unsere Liste ersetzt */
#page-data #donations-list,
#page-data #jsShowMoreDonationsLink { display: none !important; }
```

### JS-Feld
```js
/* Dauerspenden-Barometer: ÜBER dem FRBox-Barometer einsetzen (FRBox-Barometer bleibt) */
(function () {
  function run() {
    if (!document.getElementById('sos-dauer-baro')) {
      var cfd = new URLSearchParams(location.search).get('cfd');
      var anchor = document.querySelector('#page-data .progress');
      if (cfd && anchor && anchor.parentNode) {
        var w = document.createElement('div'); w.id = 'sos-dauer-baro'; w.style.cssText = 'margin:0 0 16px;';
        var f = document.createElement('iframe');
        f.src = 'https://env-soshumanity-gooddev.kinsta.cloud/wp-json/sos/v1/barometer/embed'
              + '?cfd=' + encodeURIComponent(cfd) + '&metric=count&transparent=1'
              + '&barometer_text_current=' + encodeURIComponent('Schon {donor_count} Dauerspender:innen!');
        f.style.cssText = 'width:100%;border:0;height:200px;display:block;';
        w.appendChild(f);
        anchor.parentNode.insertBefore(w, anchor);
      }
    }
    /* Spenderliste: FRBox-Liste durch unsere ersetzen */
    if (!document.getElementById('sos-dauer-liste')) {
      var cfd2 = new URLSearchParams(location.search).get('cfd');
      var list = document.querySelector('#donations-list');
      if (cfd2 && list && list.parentNode) {
        var lw = document.createElement('div'); lw.id = 'sos-dauer-liste'; lw.style.cssText = 'margin:0 0 16px;';
        var lf = document.createElement('iframe');
        lf.src = 'https://env-soshumanity-gooddev.kinsta.cloud/wp-json/sos/v1/donorlist/embed'
               + '?cfd=' + encodeURIComponent(cfd2)
               + '&transparent=1&title=' + encodeURIComponent('Wir retten mit: ({count})');
        lf.style.cssText = 'width:100%;border:0;height:600px;display:block;';
        lw.appendChild(lf);
        list.style.display = 'none';
        var more = document.getElementById('jsShowMoreDonationsLink');
        if (more) more.style.display = 'none';
        list.parentNode.insertBefore(lw, list);
      }
    }
  }
  if (document.readyState !== 'loading') run(); else document.addEventListener('DOMContentLoaded', run);
  setTimeout(run, 800); setTimeout(run, 2000);
})();
```

Relevante FRBox-DOM-Anker: `#page-data .progress` (FRBox-Barometer),
`#donations-list` (Spenderliste), `#jsShowMoreDonationsLink` („mehr anzeigen").

---

## 6. Deploy & lokales Testen

- **Reine PHP-Änderungen** (alle `inc/`-Dateien hier) brauchen **keinen Build**.
- Asset-Änderungen (Barometer-Komponente JS/CSS): `npm run build` (Vite → `dist/`).
- **Stage/Env:** Theme per Kinsta auf `env-soshumanity-gooddev.kinsta.cloud` übertragen
  (nur Theme; DB der Env bleibt). **Produktion** läuft über Backenforce.
- ⚠️ Env nutzt ein **anderes FRBox-Widget** (`cfh=y6qbmuxd`) → echte Aktionen rendern dort
  nicht; die echte Aktion + FRBox-Inhalt nur auf **Prod**. Unsere Embeds laufen überall
  (ziehen Prod-Daten via Token).

Lokaler API-/Funktionstest (DevKinsta, **php8.1** nötig – Default-`php` ist 7.4 und bricht den Bootstrap):
```bash
docker exec devkinsta_fpm php8.1 /usr/local/bin/wp eval \
  "echo json_encode(\Flynt\FRBox\get_action_recurring_by_cfd('su995'));" \
  --path=/www/kinsta/public/soshumanity --allow-root
```
Endpoints lokal: `https://soshumanity.local:<port>/wp-json/sos/v1/{barometer|donorlist}/embed?cfd=su995`

---

## 7. Demo ohne Produktionsänderung (Bookmarklet)

Echte Aktion auf `sos-humanity.org` öffnen (Cookies akzeptieren), dann via DevTools-Console
das Barometer einfügen (lädt von der Env). Siehe Snippet in Abschnitt 5 (ohne die `display:none`-Zeilen,
wenn man nichts ausblenden will). Nur im eigenen Browser, keine Live-Änderung.

---

## 8. Offene Punkte / TODO

- [ ] **Metrik final** mit SOS klären: Anzahl Dauerspender (`metric=count`) vs. monatliche
      Summe (`metric=sum`; braucht Intervall-Normalisierung über das verknüpfte `recurring`).
- [ ] **Ziel pro Aktion**: aktuell dynamisches Auto-Ziel. Optionen für individuelles Ziel:
      FRBox-Custom-Field je Aktion (aus `fb_custom_fields` lesen) **oder** WP-Mapping (cfd→Ziel),
      mit Auto-Ziel als Fallback.
- [ ] **An/aus**: Barometer auf allen Aktionen automatisch vs. pro Aktion schaltbar.
- [ ] **Produktionsintegration**: Embeds auf Prod deployen, iframe-URLs auf `sos-humanity.org`
      umstellen, FRBox-Snippets dauerhaft setzen (CSS/JS ist **global für alle Aktionen**!).
- [ ] **Auto-Höhe** der Listen-/Barometer-iframes via `postMessage` (lange Listen, z.B. srrds 900+).
- [ ] **Wunsch 3** (noch offen): Aktion als „nur Dauerspende" deklarieren → Intervall im
      FRBox-Formular per iframe-JS einschränken.
- [ ] **Datenschutz**: Spendenart pro Namen wird öffentlich – mit SOS bestätigt halten.
- [ ] **Aufräumen**: temporären Debug-Endpoint entfernen → `inc/frboxBarometerDebug.php`
      löschen und die Zeile `'/wp-json/sos/v1/barometer/debug'` aus `inc/disableRestApi.php`.

---

## 9. Referenz-Werte (Beispiel su995)
`fb_fundraising_page_id` = 72512 · 59 Spenden / 2.418,40 € · davon 10 Dauerspende-Setups (215 €) / 49 Einzelspenden.
