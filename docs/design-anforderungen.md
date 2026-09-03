# Design-Anforderungen

Dieses Dokument leitet aus dem in [`project.md`](../project.md) beschriebenen Funktionsumfang und dem
dort festgelegten Technologie-Stack (Symfony 7 · Bootstrap 5 · Symfony UX React) konkrete
Gestaltungsanforderungen für die zwölf fachlichen Funktionen ab. Es ersetzt keine Mockups, sondern
legt fest, **mit welchen Bausteinen des Stacks** welche Anforderung umgesetzt wird, damit Design und
Implementierung konsistent bleiben.

---

## 1. Grundlagen aus dem Tech-Stack

| Baustein | Vorgabe | Konsequenz für das Design |
|---|---|---|
| **Bootstrap 5** | Sass-Import statt CDN (offener Punkt in `project.md`) | Eigene Sass-Variablen (Farben, Radien, Spacing) sind möglich; kein zweites Laden von Bootstrap-CSS |
| **Twig + `bootstrap_5_layout.html.twig`** | Formularthema global gesetzt (`config/packages/twig.yaml`) | Alle Symfony-Forms rendern automatisch im Bootstrap-Stil inkl. Floating Labels – kein eigenes Formular-Markup nötig |
| **Symfony UX React** | React-Komponenten werden als „Inseln" in Twig eingebunden (`<twig:react:...>`), kein SPA-Routing | React nur dort einsetzen, wo eine Seite **innerhalb sich selbst** interaktiv sein muss; Seitennavigation bleibt serverseitig (Twig-Routen) |
| **Symfony Security (Form-Login)** | Alle Seiten außer `/login`, `/register`, `/logout` erfordern `ROLE_USER` | Jede Ansicht (außer Freigabe-Link, siehe §5) braucht einen eingeloggten Layout-Rahmen (Navbar mit Logout) |
| **Übersetzungen (`messages.de.json`)** | Anwendung ist einsprachig Deutsch | Keine Sprachumschaltung im UI einplanen; alle Texte/Fehlermeldungen deutsch |
| **AssetMapper (Node-frei)** | Kein Bundler-Build für Drittbibliotheken | Zusätzliche JS-Abhängigkeiten müssen über den AssetMapper (Importmap) eingebunden werden, nicht per npm-Paket vorausgesetzt werden |

---

## 2. Seitenstruktur (aus dem Funktionsumfang abgeleitet)

Jede Zeile der Funktionstabelle aus `project.md` benötigt eine eigene Ansicht bzw. einen eigenen
UI-Baustein:

| Funktion | Ansicht | Bootstrap-Bausteine |
|---|---|---|
| 1 Personen verwalten | Personenliste + Formular (Anlegen/Bearbeiten) | `table`/`card`-Liste, `form_row`-Formular in Modal oder eigener Route |
| 2 Anlässe verwalten | Anlassliste | Liste mit Badge „Standard" für Geburtstag/Weihnachten, restliche frei löschbar |
| 3 Vergangene Geschenke dokumentieren | Teil der Personen-Detailansicht | `card`-Liste, gefiltert auf `status = verschenkt` |
| 4 Geschenkidee erfassen | Formular (von Personendetail aus) | `bootstrap_5_layout`-Formular, vorbelegte Person |
| 5 Ideen anreichern (Anhänge) | Anhang-Verwaltung an der Idee | Text/Link als Listeneintrag, Bild als `img-thumbnail`; Upload-Feld |
| 6 Idee → Geschenk umwandeln | Statuswechsel-Aktion | Button/Select für `status`-Übergang (idee → geplant → besorgt → verschenkt), kein Seiten- oder Tabellenwechsel |
| 7 Personen-Detailansicht | Kombinierte Ansicht | Zwei Abschnitte (Ideen / vergangene Geschenke) auf einer Seite, z. B. Tabs oder zwei `card`-Spalten |
| 8 Geburtstags-Benachrichtigung | Dashboard-Widget/Alert | `alert`-Komponente oder Benachrichtigungs-Liste, gespeist aus `Benachrichtigung` |
| 9 Weihnachts-Statusmeldungen (optional) | Dashboard-Widget | Gleiche Komponente wie Fkt. 8, anderer `typ` |
| 10 Ideen per Link teilen (optional) | Öffentliche Freigabe-Ansicht | Eigenes, reduziertes Layout ohne Navbar/Login (siehe §5) |
| 11 Vollständige Liste als HTML (optional) | Druck-/Exportansicht | Eigenes Twig-Template ohne interaktive Elemente, druckfreundlich (`@media print`) |
| 12 Automatische Ideengenerierung (optional) | Vorschlagsliste mit Übernahme-Aktion | Liste von Vorschlägen mit „Übernehmen"-Button je Eintrag |

---

## 3. Statusdarstellung `Geschenk.status`

Da `Geschenk` sowohl Ideen als auch verschenkte Geschenke über ein einziges `status`-Feld abbildet
(zentrale Entwurfsentscheidung laut `project.md`), muss der Status in jeder Listen-/Kartenansicht
optisch eindeutig erkennbar sein. Empfohlene Bootstrap-Badge-Farben:

| Status | Bedeutung | Badge |
|---|---|---|
| `idee` | Geschenkidee, noch nicht geplant | `bg-secondary` |
| `geplant` | Für einen Anlass vorgesehen | `bg-info` |
| `besorgt` | Gekauft/besorgt, aber noch nicht übergeben | `bg-warning` |
| `verschenkt` | Übergeben, abgeschlossen | `bg-success` |

Diese Zuordnung ist konsistent überall dort zu verwenden, wo `Geschenk`-Einträge dargestellt werden
(Personen-Detailansicht, Weihnachts-Status, Dashboard).

---

## 4. Wo React (statt reinem Twig) eingesetzt wird

Symfony UX React ist für Seiteninhalte gedacht, die **ohne Reload** reagieren müssen. Kandidaten aus
dem Funktionsumfang:

- **Statuswechsel einer Idee/eines Geschenks** (Fkt. 6): Auswahl/Drag-Aktion aktualisiert `status` per
  Fetch-Request, ohne die Personen-Detailansicht neu zu laden.
- **Aufgaben-Checkliste** (`Aufgabe.erledigt`): Abhaken einer Aufgabe soll sofort visuell reagieren.
- **Automatische Ideengenerierung** (Fkt. 12, optional): Vorschlagsliste mit Übernahme-Aktion pro
  Zeile, ohne dass die gesamte Seite neu gerendert wird.

Alles andere (Formulare, Listen, Navigation) bleibt klassisches Twig/Symfony-Form-Rendering –
React ist die Ausnahme für gezielt interaktive Bausteine, nicht die Basis des Layouts.

---

## 5. Freigabe-Ansicht (öffentlich, ohne Login)

Die per Token aufrufbare Freigabe-Route (Fkt. 10) liegt außerhalb der `ROLE_USER`-Zugriffskontrolle
und darf daher **kein** Bearbeiten, keine Navbar mit Logout und keine internen Links (Personenverwaltung
etc.) zeigen. Anforderungen:

- Eigenes, reduziertes Basis-Layout (kein `base.html.twig` mit voller Navigation) oder ein separater
  Twig-Block, der Navigation/Logout ausblendet.
- Nur Leseansicht der Ideen einer Person (kein Zugriff auf andere Personen, keine Statusänderung).
- Kein Hinweis auf interne Struktur (IDs, andere Personen) im Markup.

---

## 6. Responsive Anforderungen

- Mobile-first nach Bootstrap-Breakpoints (`sm`/`md`/`lg`); Personenliste und Geschenklisten müssen auf
  Schmalbildschirmen (Smartphone) als Karten statt Tabelle darstellbar sein.
- Formulare (Bootstrap-Floating-Labels) müssen auf einspaltigem Layout unter `md` umbrechen.
- Druckansicht (Fkt. 11) ignoriert Breakpoints und optimiert stattdessen für `@media print` (keine
  Navbar, keine Buttons, klarer Seitenumbruch pro Person).

---

## 7. Offene Design-Entscheidungen

Diese Punkte sind in `project.md` unter „Offene Punkte" als technische Aufgaben vermerkt, haben aber
direkte Design-Konsequenzen und sollten vor der Umsetzung geklärt werden:

- **Bootstrap-Einbindung**: Umstieg von CDN auf Sass-Import wird eigene Theme-Variablen (Farben,
  Radien) ermöglichen – diese sollten hier ergänzt werden, sobald festgelegt.
- **Enums statt VARCHAR** (`Geschenk.status`, `Anhang.typ`): Wirkt sich nicht auf das Design aus,
  wohl aber auf mögliche zukünftige Statuswerte/Badge-Zuordnungen in §3.
- **Benachrichtigungsversand** (persistiert vs. zur Laufzeit berechnet): Bestimmt, ob das
  Benachrichtigungs-Widget (§2, Fkt. 8/9) eine „gelesen"-Markierung anzeigen muss oder rein
  informativ ist.
