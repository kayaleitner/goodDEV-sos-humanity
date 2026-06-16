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
| `inc/rest-action-config.php` | JSON-Endpoint: per-Aktion-Flags aus FRBox-Custom-Fields (+ CORS). |
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

### Per-Aktion-Config (JSON + CORS)
```
/wp-json/sos/v1/action/config?cfd=su995
  → { "cfd":"su995", "barometer":true, "list":true, "only_monthly":false }
```
Wird vom FRBox-JS abgefragt, um pro Aktion zu entscheiden, was eingeblendet wird.

---

## 4b. Per-Aktion-Steuerung über FRBox-Custom-Fields

SOS entscheidet **pro Spendenaktion** per Häkchen, was angezeigt wird. Dafür gibt es
3 FRBox-Custom-Fields (Checkbox, gesetzt = Wert `'1'`, sonst leer):

| Custom-Field-ID | Bedeutung | Config-Flag |
|---|---|---|
| 16330 | „Dauerspenden-Barometer anzeigen" | `barometer` |
| 16329 | „Anzeige von Dauer- & Einzelspenden in der Namensliste" | `list` |
| 16328 | „Spenden-Intervall nur monatlich" (Wunsch 3, Formular) | `only_monthly` |

Gelesen in `inc/frboxFundraisingPageData.php` (`get_action_flags()` / `custom_field_flags()`,
IDs als Konstanten). Truthy-Check akzeptiert `1/ja/yes/true/on`.

---

## 5. FRBox-Backend-Snippets (einzufügen im FRBox-Backend, global)

> Diese laufen **innerhalb** des FRBox-iframes. `<iframe src>` zeigt aktuell auf die
> **Kinsta-Stage** (Demo). Für Produktion: Embeds auf Prod deployen und URL auf
> `https://sos-humanity.org/...` ändern.

### CSS-Feld
**Leer.** Kein statisches Ausblenden mehr – das Ausblenden der FRBox-Liste passiert
**bedingt im JS** (nur wenn das `list`-Flag der Aktion an ist), sonst würde die
FRBox-Liste auch bei nicht-aktivierten Aktionen verschwinden.

### JS-Feld (config-gesteuert)
Fragt pro Aktion `/action/config` ab und blendet **nur ein, was aktiviert ist**:
```js
/* Dauerspenden: per-Aktion gesteuert über FRBox-Custom-Fields */
(function () {
  var BASE = 'https://env-soshumanity-gooddev.kinsta.cloud';   // PROD: https://sos-humanity.org
  var cfd = new URLSearchParams(location.search).get('cfd');
  if (!cfd) return;
  var flags = null;

  function injectBarometer() {
    if (document.getElementById('sos-dauer-baro')) return;
    var anchor = document.querySelector('#page-data .progress');
    if (!anchor || !anchor.parentNode) return;
    var w = document.createElement('div'); w.id = 'sos-dauer-baro'; w.style.cssText = 'margin:0 0 16px;';
    var f = document.createElement('iframe');
    f.src = BASE + '/wp-json/sos/v1/barometer/embed?cfd=' + encodeURIComponent(cfd)
          + '&metric=count&transparent=1&barometer_text_current=' + encodeURIComponent('Schon {donor_count} Dauerspender:innen!');
    f.style.cssText = 'width:100%;border:0;height:200px;display:block;';
    w.appendChild(f); anchor.parentNode.insertBefore(w, anchor);   // FRBox-Barometer bleibt darunter
  }
  function injectList() {
    if (document.getElementById('sos-dauer-liste')) return;
    var list = document.querySelector('#donations-list');
    if (!list || !list.parentNode) return;
    var lw = document.createElement('div'); lw.id = 'sos-dauer-liste'; lw.style.cssText = 'margin:0 0 16px;';
    var lf = document.createElement('iframe');
    lf.src = BASE + '/wp-json/sos/v1/donorlist/embed?cfd=' + encodeURIComponent(cfd)
           + '&transparent=1&title=' + encodeURIComponent('Wir retten mit: ({count})');
    lf.style.cssText = 'width:100%;border:0;height:600px;display:block;';
    lw.appendChild(lf);
    list.style.display = 'none';
    var more = document.getElementById('jsShowMoreDonationsLink'); if (more) more.style.display = 'none';
    list.parentNode.insertBefore(lw, list);
  }
  function apply() {
    if (!flags) return;
    if (flags.barometer) injectBarometer();
    if (flags.list) injectList();
    /* flags.only_monthly → Wunsch 3 (Formular auf monatlich beschränken) – folgt separat */
  }
  fetch(BASE + '/wp-json/sos/v1/action/config?cfd=' + encodeURIComponent(cfd))
    .then(function (r) { return r.json(); })
    .then(function (j) { flags = j; apply(); setTimeout(apply, 1000); setTimeout(apply, 2500); })
    .catch(function () {});
})();
```

Relevante FRBox-DOM-Anker: `#page-data .progress` (FRBox-Barometer),
`#donations-list` (Spenderliste), `#jsShowMoreDonationsLink` („mehr anzeigen").

---

## 5b. Wunsch 3 – Formular auf „nur monatlich" beschränken

> ⚠️ Dieses Snippet kommt in das **Formular-CSS/JS-Feld der FRBox** – das ist ein
> **eigenes Feld** (dort liegt auch `sosSpendenformular.js`), nicht das Aktionsseiten-Feld
> aus Abschnitt 5. Das Formular wird unter `…/spendenaktion/?cfs=p&cfd=XXXXX#cff`
> aufgerufen → der `cfd` ist im Formular-Kontext verfügbar.

Greift bei Aktionen mit Flag `only_monthly` (Custom-Field 16328). Wirkung:
Intervall-Auswahl ausblenden, im `#payment_interval` nur **monatlich** (Wert `1`)
behalten/vorwählen, Panel-Überschrift „Meine Spende für …" → „Meine **monatliche**
Spende für …" (DE/EN/IT). Ein `MutationObserver` hält den Titel, da
`sosSpendenformular.js` (`applyPanelTitles`) ihn per Timer wiederholt neu setzt.

### Variante A – einfach (kann kurz flackern)
Intervall/Titel werden erst nach der Config-Antwort angepasst → auf `only_monthly`-Aktionen
sieht man die Auswahl/den Titel **kurz**, dann wird umgestellt.

```js
/* Wunsch 3 (einfach) */
(function () {
  var BASE = 'https://env-soshumanity-gooddev.kinsta.cloud';   // PROD: https://sos-humanity.org
  var cfd = new URLSearchParams(location.search).get('cfd');
  if (!cfd) return;
  function lang() { var l = (document.documentElement.getAttribute('lang') || 'de').toLowerCase(); return l.indexOf('en') === 0 ? 'en' : (l.indexOf('it') === 0 ? 'it' : 'de'); }
  function isMonthly(v, t) { return String(v) === '1' || /monat|month|mensil/i.test(t || ''); }
  function restrict() {
    var sel = document.getElementById('payment_interval');
    if (sel) {
      Array.prototype.slice.call(sel.options).forEach(function (o) { if (!isMonthly(o.value, o.text)) o.remove(); });
      var m = Array.prototype.slice.call(sel.options).filter(function (o) { return isMonthly(o.value, o.text); })[0];
      if (m && sel.value !== m.value) { sel.value = m.value; sel.dispatchEvent(new Event('change', { bubbles: true })); }
    }
    var r = document.querySelector('.interval-radios'); if (r) r.style.display = 'none';
  }
  function fixTitle() {
    var el = document.querySelector('#amountBox .panel-title'); if (!el) return;
    var txt = el.textContent; if (/monatlich|monthly|mensil/i.test(txt)) return;
    var l = lang(), n = l === 'en' ? txt.replace(/\bMy donation\b/i, 'My monthly donation')
      : l === 'it' ? txt.replace(/\bdonazione\b/i, 'donazione mensile')
      : txt.replace(/\bMeine Spende\b/i, 'Meine monatliche Spende');
    if (n !== txt) el.textContent = n;
  }
  function enable() {
    restrict(); fixTitle();
    [400, 1000, 2000].forEach(function (ms) { setTimeout(function () { restrict(); fixTitle(); }, ms); });
    var box = document.getElementById('amountBox');
    if (box && window.MutationObserver) new MutationObserver(fixTitle).observe(box, { childList: true, subtree: true, characterData: true });
  }
  fetch(BASE + '/wp-json/sos/v1/action/config?cfd=' + encodeURIComponent(cfd))
    .then(function (r) { return r.json(); }).then(function (j) { if (j && j.only_monthly) enable(); }).catch(function () {});
})();
```

### Variante B – flackerfrei (empfohlen)
Versteckt Intervall + Titel **sofort synchron** (CSS-Klasse `sos-pending`) und zeigt erst
nach der Config-Antwort den finalen Zustand. Auf `only_monthly`-Aktionen sieht man die
Auswahl gar nicht erst; der Titel reserviert via `visibility` seinen Platz (kein Layout-Sprung).
Trade-off: auf Nicht-monatlichen Aktionen erscheinen Intervall/Titel ~200 ms verzögert.

```js
/* Wunsch 3 (flackerfrei) */
(function () {
  var BASE = 'https://env-soshumanity-gooddev.kinsta.cloud';   // PROD: https://sos-humanity.org
  var cfd = new URLSearchParams(location.search).get('cfd');
  if (!cfd) return;
  var st = document.createElement('style');
  st.textContent = '.sos-pending .interval-radios,.sos-monthly .interval-radios{display:none!important;}'
                 + '.sos-pending #amountBox .panel-title{visibility:hidden!important;}';
  (document.head || document.documentElement).appendChild(st);
  var root = document.documentElement; root.classList.add('sos-pending');
  function done() { root.classList.remove('sos-pending'); }
  function lang() { var l = (root.getAttribute('lang') || 'de').toLowerCase(); return l.indexOf('en') === 0 ? 'en' : (l.indexOf('it') === 0 ? 'it' : 'de'); }
  function isMonthly(v, t) { return String(v) === '1' || /monat|month|mensil/i.test(t || ''); }
  function restrictSelect() {
    var sel = document.getElementById('payment_interval'); if (!sel) return;
    Array.prototype.slice.call(sel.options).forEach(function (o) { if (!isMonthly(o.value, o.text)) o.remove(); });
    var m = Array.prototype.slice.call(sel.options).filter(function (o) { return isMonthly(o.value, o.text); })[0];
    if (m && sel.value !== m.value) { sel.value = m.value; sel.dispatchEvent(new Event('change', { bubbles: true })); }
  }
  function fixTitle() {
    var el = document.querySelector('#amountBox .panel-title'); if (!el) return;
    var txt = el.textContent; if (/monatlich|monthly|mensil/i.test(txt)) return;
    var l = lang(), n = l === 'en' ? txt.replace(/\bMy donation\b/i, 'My monthly donation')
      : l === 'it' ? txt.replace(/\bdonazione\b/i, 'donazione mensile')
      : txt.replace(/\bMeine Spende\b/i, 'Meine monatliche Spende');
    if (n !== txt) el.textContent = n;
  }
  function enableMonthly() {
    root.classList.add('sos-monthly'); restrictSelect(); fixTitle();
    [400, 1000, 2000].forEach(function (ms) { setTimeout(function () { restrictSelect(); fixTitle(); }, ms); });
    var box = document.getElementById('amountBox');
    if (box && window.MutationObserver) new MutationObserver(fixTitle).observe(box, { childList: true, subtree: true, characterData: true });
    done();
  }
  fetch(BASE + '/wp-json/sos/v1/action/config?cfd=' + encodeURIComponent(cfd))
    .then(function (r) { return r.json(); }).then(function (j) { if (j && j.only_monthly) enableMonthly(); else done(); }).catch(done);
  setTimeout(done, 4000);   // Sicherheitsnetz
})();
```

Annahme: `payment_interval`-Wert `1` = monatlich (+ Text-Match `monat|month|mensil`).
Falls andere Werte → Match in `isMonthly()` anpassen.

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
- [x] **An/aus pro Aktion**: gelöst über FRBox-Custom-Fields (16330 Barometer, 16329 Liste) → `/action/config`.
- [x] **Wunsch 3**: Flag `only_monthly` (16328) + Formular-Snippet vorhanden (s. Abschnitt 5b,
      2 Varianten) – ins **Formular-JS-Feld** der FRBox einsetzen + auf einer `only_monthly`-Aktion testen.
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
