<?php

namespace Database\Seeders;

use App\Models\LearningModule;
use Illuminate\Database\Seeder;

class LearningModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            // B1
            [
                'title' => 'Wechselpräpositionen im Deutschen',
                'category' => 'Grammar',
                'level' => 'B1',
                'summary' => 'Lernen Sie die 9 Präpositionen kennen, die je nach Kontext Akkusativ oder Dativ verlangen.',
                'content' => "# Wechselpräpositionen

Wechselpräpositionen können entweder mit dem **Dativ** (Wo?) oder dem **Akkusativ** (Wohin?) stehen. Es gibt genau neun Wechselpräpositionen:

* **an, auf, hinter, in, neben, über, unter, vor, zwischen**

## Dativ (Position / Wo?)
Wenn wir nach einer Position fragen, benutzen wir den **Dativ**:
* *Das Buch liegt auf **dem** Tisch.* (Wo liegt es?)
* *Ich sitze in **der** Küche.* (Wo sitze ich?)

## Akkusativ (Richtung / Wohin?)
Wenn wir nach einer Bewegung oder Richtungsänderung fragen, benutzen wir den **Akkusativ**:
* *Ich lege das Buch auf **den** Tisch.* (Wohin lege ich es?)
* *Ich gehe in **die** Küche.* (Wohin gehe ich?)

### Typische Verben:
* **Dativ:** liegen, sitzen, stehen, hängen
* **Akkusativ:** legen, setzen, stellen, hängen (Aktion)",
                'is_published' => true,
            ],
            [
                'title' => 'Wichtiger B1-Wortschatz für den Beruf',
                'category' => 'Vocabulary',
                'level' => 'B1',
                'summary' => 'Die wichtigsten Nomen, Verben und Phrasen für den Büroalltag auf Deutsch.',
                'content' => "# B1 Berufswortschatz

Für eine erfolgreiche Kommunikation am Arbeitsplatz benötigen Sie spezifische Ausdrücke. Hier sind die wichtigsten:

| Wort / Phrase | Bedeutung | Beispiel |
|---|---|---|
| **sich bewerben um** | apply for | *Ich bewerbe mich um eine neue Stelle.* |
| **der Arbeitsvertrag** | employment contract | *Er unterschreibt morgen den Arbeitsvertrag.* |
| **die Überstunde** | overtime | *Letzte Woche musste ich fünf Überstunden machen.* |
| **das Gehalt** | salary | *Ich bin mit meinem Gehalt zufrieden.* |
| **die Besprechung** | meeting / conference | *Unsere wöchentliche Besprechung findet montags statt.* |",
                'is_published' => true,
            ],

            // B2
            [
                'title' => 'Zweiteilige Konnektoren',
                'category' => 'Grammar',
                'level' => 'B2',
                'summary' => 'Erfahren Sie, wie Sie Sätze mit zweiteiligen Konnektoren wie \"sowohl ... als auch\" verbinden.',
                'content' => "# Zweiteilige Konnektoren

Zweiteilige Konnektoren verbinden Sätze oder Satzglieder miteinander. Sie drücken komplexe Beziehungen aus:

## 1. sowohl ... als auch (Kopulativ - zweifache Aufzählung)
* *Er spricht **sowohl** Deutsch **als auch** Englisch.* (Beides trifft zu)

## 2. nicht nur ... sondern auch (Steigerung)
* *Sie arbeitet **nicht nur** fleißig, **sondern** ist **auch** sehr kreativ.*

## 3. entweder ... oder (Disjunktiv - Alternative)
* *Wir können **entweder** ins Kino gehen **oder** zu Hause bleiben.* (Nur eine Option ist möglich)

## 4. weder ... noch (Negative Aufzählung)
* *Ich habe **weder** Zeit **noch** Geld für einen Urlaub.* (Keines von beidem)",
                'is_published' => true,
            ],
            [
                'title' => 'Redemittel für Diskussionen und Argumentation',
                'category' => 'Vocabulary',
                'level' => 'B2',
                'summary' => 'Drücken Sie Ihre Meinung differenziert aus und reagieren Sie überzeugend auf Gegenargumente.',
                'content' => "# Redemittel B2: Diskussionen

Um in Gesprächen oder der mündlichen Prüfung sachlich zu argumentieren, eignen sich folgende Formulierungen:

### Eigene Meinung äußern
* *Meiner Meinung/Ansicht nach...*
* *Ich bin der Überzeugung, dass...*
* *Ich stehe auf dem Standpunkt, dass...*

### Zustimmung ausdrücken
* *Da kann ich Ihnen nur zustimmen.*
* *Das sehe ich ganz genauso.*
* *Ich teile diese Ansicht voll und ganz.*

### Widerspruch formulieren
* *Das stimmt zwar, aber...*
* *Hier muss ich leider widersprechen.*
* *Ich bezweifle, dass dieses Argument stichhaltig ist.*",
                'is_published' => true,
            ],

            // C1
            [
                'title' => 'Nomen-Verb-Verbindungen',
                'category' => 'Grammar',
                'level' => 'C1',
                'summary' => 'Funktionsverbgefüge richtig verstehen und anwenden – essenziell für ein hohes C1-Niveau.',
                'content' => "# Nomen-Verb-Verbindungen (Funktionsverbgefüge)

Im C1-Bereich (insbesondere im schriftlichen Ausdruck) sind Nomen-Verb-Verbindungen sehr häufig. Sie klingen formeller und präziser.

* **zur Verfügung stehen** (passiv) / **zur Verfügung stellen** (aktiv)
  * *Die Bücher stehen Ihnen jederzeit zur Verfügung.* (= Sie können sie nutzen)
  * *Die Bibliothek stellt die Bücher zur Verfügung.* (= stellt sie bereit)

* **in Kauf nehmen**
  * *Man muss die Nachteile in Kauf nehmen.* (= akzeptieren)

* **Rücksicht nehmen auf (+ Akkusativ)**
  * *Wir müssen Rücksicht auf unsere Nachbarn nehmen.* (= rücksichtsvoll sein)

* **in Frage kommen**
  * *Diese Option kommt überhaupt nicht in Frage.* (= ist unmöglich/ausgeschlossen)",
                'is_published' => true,
            ],
            [
                'title' => 'Wortschatz für wissenschaftliche Arbeiten',
                'category' => 'Vocabulary',
                'level' => 'C1',
                'summary' => 'Akademische Formulierungen und Fachwortschatz für anspruchsvolle Texte.',
                'content' => "# Akademischer C1 Wortschatz

Diese Begriffe und Formulierungen helfen Ihnen dabei, wissenschaftliche bzw. akademische Texte zu verfassen:

* **hervorheben** (emphasize)
  * *Es muss hervorgehoben werden, dass die Ergebnisse eindeutig sind.*
* **implizieren** (imply)
  * *Diese Theorie impliziert weitreichende Konsequenzen für die Praxis.*
* **verifizieren / falsifizieren** (verify / falsify)
  * *Die Hypothese konnte durch Experimente verifiziert werden.*
* **im Hinblick auf (+ Akkusativ)** (with regard to)
  * *Im Hinblick auf die Globalisierung müssen wir neue Strategien entwickeln.*",
                'is_published' => true,
            ],

            // C2
            [
                'title' => 'Komplexe Syntax: Partizipialattribute',
                'category' => 'Grammar',
                'level' => 'C2',
                'summary' => 'Verkürzung von Relativsätzen durch Partizip I und Partizip II im C2-Kontext.',
                'content' => "# Partizipialattribute (C2)

In gehobenen journalistischen oder wissenschaftlichen Texten werden Relativsätze oft zu Partizipialkonstruktionen verkürzt. Sie stehen vor dem Nomen.

## Partizip I (Aktiv, Gleichzeitigkeit)
* *Der Fahrgast, der laut lacht.* $\rightarrow$ *Der laut **lachende** Fahrgast.*
* *Das Problem, das andauert.* $\rightarrow$ *Das **andauernde** Problem.*

## Partizip II (Passiv, Vorzeitigkeit)
* *Das Gesetz, das gestern beschlossen wurde.* $\rightarrow$ *Das gestern **beschlossene** Gesetz.*
* *Der Bericht, der verfasst worden ist.* $\rightarrow$ *Der **verfasste** Bericht.*

## Erweiterte Partizipialattribute
Diese Konstruktionen können sehr lang werden:
* *Die **von den Delegierten auf der gestrigen Konferenz kontrovers diskutierten** Gesetzesänderungen...*
  (Relativsatz: *Die Gesetzesänderungen, die von den Delegierten auf der gestrigen Konferenz kontrovers diskutiert wurden...*)",
                'is_published' => true,
            ],
            [
                'title' => 'Idiomatische Redewendungen für Muttersprachen-Niveau',
                'category' => 'Vocabulary',
                'level' => 'C2',
                'summary' => 'Verleihen Sie Ihrem Ausdruck mit sprichwörtlichen Redensarten den letzten Schliff.',
                'content' => "# Idiomatische Ausdrücke (C2)

Für ein echtes C2-Niveau ist das Beherrschen von Redewendungen unerlässlich. Sie zeigen ein tiefes Gespür für die deutsche Sprache:

* **etwas an den Nagel hängen**
  * Bedeutung: etwas aufgeben / eine Karriere beenden
  * Beispiel: *Nach 20 Jahren hat er seinen Beruf an den Nagel gehängt.*

* **den Teufel an die Wand malen**
  * Bedeutung: das Schlimmste befürchten / pessimistisch sein
  * Beispiel: *Mal nicht gleich den Teufel an die Wand! Es wird schon alles gut gehen.*

* **auf großem Fuß leben**
  * Bedeutung: viel Geld ausgeben / luxuriös leben
  * Beispiel: *Seit ihrem Lottogewinn lebt sie auf großem Fuß.*

* **ins Fettnäpfchen treten**
  * Bedeutung: unabsichtlich etwas Unangenehmes sagen / jemanden beleidigen
  * Beispiel: *Bei der Begrüßung der Gäste bin ich direkt ins Fettnäpfchen getreten.*",
                'is_published' => true,
            ],
        ];

        foreach ($modules as $data) {
            LearningModule::create($data);
        }
    }
}
